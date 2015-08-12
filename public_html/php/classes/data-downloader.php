<?php

/**
 * This class will download ABQ data about restaurant inspections
 *
 * @author Skyler Rexroad skyrex1095@gmail.com
 */
class DataDownloader {

    /*
     * businesses: http://data.cabq.gov/business/LIVES/businesses.csv
     * inspections: http://data.cabq.gov/business/LIVES/inspections.csv
     * violations: http://data.cabq.gov/business/LIVES/violations.csv
     * old xml: http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml
    */

    /**
     * Determines wether or not we can grab a file
     *
     * @param string $url url to check for
     * @return bool whether the file exists or not
     */
    public static function checkExists($url) {
        // Get the stream data
        $streamData = DataDownloader::getMetaData($url);

        // Get the array that contains the attribute we want
        $wrapperData = $streamData["wrapper_data"];

        // Loop through to find the "HTTP" attribute
        $http = "";
        foreach($wrapperData as $data) {
            if(strpos($data, "HTTP") !== false) {
                $http = $data;
                break;
            }
        }

        if(strpos($http, "200 OK") === false) {
            echo "file not found <br/>";
            return false;
        } else {
            echo "file found <br/>";
            return true;
        }
    }

    /**
     * Gets the metadata from a file url
     *
     * @param string $url url to grab from
     * @return mixed stream data
     */
    public static function getMetaData($url) {
        $context = stream_context_create(array("http" => array("method" => "HEAD")));
        $fd = fopen($url, "rb", false, $context);
//		var_dump(stream_get_meta_data($fd));

        // Grab the stream data
        $streamData = stream_get_meta_data($fd);

        fclose($fd);

        return $streamData;
    }

    /**
     * Gets the last modified attribute from a file url
     *
     * @param string $url url to check
     * @return string "Last-Modified" attribute
     */
    public static function getLastModified($url) {
        // Get the stream data
        $streamData = DataDownloader::getMetaData($url);

        // Get the wrapper data that contains the "Last-Modified" attribute
        $wrapperData = $streamData["wrapper_data"];

        // Loop through to find the "Last-Modified" attribute
        $lastModified = "";
        foreach($wrapperData as $data) {
            if(strpos($data, "Last-Modified") !== false) {
                $lastModified = $data;
                break;
            }
        }

        return $lastModified;
    }

    /**
     * Gets the "Last-Modified" date from a file url
     *
     * @param string $url url to check
     * @return DateTime date last modified
     */
    public static function getLastModifiedDate($url) {
        // Make sure the file exists
        if(DataDownloader::checkExists($url) === false) {
            throw(new RuntimeException("File not found"));
        }

        // Get the "Last-Modified" attribute
        $lastModified = DataDownloader::getLastModified($url);
        $dateString = null;

        if(strpos($lastModified, "Last-Modified") !== false) {
            // Grab the string after "Last-Modified: "
            $dateString = substr($lastModified, 15);
        }

        $date = new DateTime($dateString);
        $date->setTimezone(new DateTimeZone(date_default_timezone_get()));

        // $formattedDate = $date->format("Y-m-d H:i:s");

        return $date;
    }

    /**
     * Deletes a file or files from a directory
     *
     * @param string $path path to file
     * @param string $name filename
     * @param string $extension extension of file
     */
    public static function deleteFiles($path, $name, $extension) {
        // Delete file(s)
        $files = glob("$path$name*$extension");
        foreach($files as $file) {
            echo "glob: " . $file . "<br/>";
            unlink($file);
        }
    }

    /**
     * Downloads a file to a path from a url
     * Code modified from a stackoverflow answer
     * http://stackoverflow.com/a/9843981
     *
     * @param string $url url to grab from
     * @param string $path path to save to
     * @param string $name filename to save in
     * @param string $extension extension to save in
     * @throws Exception $e catch-all exception
     */
    public static function downloadFile($url, $path, $name, $extension) {
        // Delete old file(s)
        DataDownloader::deleteFiles($path, $name, $extension);

        // Create new file
        $newFile = null;
        $newFileName = $path . $name . DataDownloader::getLastModifiedDate($url)->getTimestamp() . ".csv";
        // echo $newFileName;

        $file = fopen($url, "rb");
        if($file) {
            $newFile = fopen($newFileName, "wb");

            if($newFile)
                while(!feof($file)) {
                    fwrite($newFile, fread($file, 1024 * 8), 1024 * 8);
                }
        }

        if($file) {
            fclose($file);
        } else {
            fclose($newFile);
        }
    }

    /**
     * Gets the date of a stored file
     *
     * @param string $path path to stored file
     * @param string $name name of stored file
     * @param string $extension extension of stored file
     * @return DateTime date of stored file
     * @throws Exception if file does not exist
     */
    public static function getDateFromStoredFile($path, $name, $extension) {
        // Get date from stored file
        $currentDateStr = null;
        $files = glob("$path$name*$extension");
        if(isset($files[0])) {
            $currentFile = $files[0];
        } else {
            throw(new Exception("No file exists"));
        }
//		echo "currentFile: " . $currentFile . "<br/>";

        // Get date from filename
        $matches = array();
        preg_match("/\\d+/", $currentFile, $matches);
        $currentDateStr = $matches[0];
//		echo "currentDateStr: " . $currentDateStr . "<br/>";

        // Create date
        $currentDate = DateTime::createFromFormat("U", $currentDateStr);
//		echo "currentDate: " . $currentDate->format("Y-m-d H:i:s") . "<br/>";

        return $currentDate;
    }

    /**
     * Saves a new version of a file if there is one
     *
     * @param string $url url to grab from
     * @param string $path path to save to
     * @param string $name filename to save in
     * @param string $extension extension to save in
     * @return boolean true if new file was downloaded, false if not
     */
    public static function downloadIfNew($url, $path, $name, $extension) {
        // Make sure the file exists
        if(DataDownloader::checkExists($url) === false) {
            throw(new RuntimeException("File not found"));
        }

        // Get date of city file
        $newDate = DataDownloader::getLastModifiedDate($url);

        // Get date of stored file
        $currentDate = null;
        if(DataDownloader::checkExists($path . $name . $extension)) {
            $currentDate = DataDownloader::getDateFromStoredFile($path, $name, $extension);
        }

        // If the city file is newer, download it
        if($currentDate !== null) {
            if($newDate > $currentDate) {
                DataDownloader::downloadFile($url, $path, $name, $extension);
                return true;
            } else {
                return false;
            }
        } else {
            DataDownloader::downloadFile($url, $path, $name, $extension);
            return true;
        }
    }
}

$businessesDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/businesses.csv");
$inspectionsDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/inspections.csv");
$violationsDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/LIVES/violations.csv");
$xmlDate = DataDownloader::getLastModifiedDate("http://data.cabq.gov/business/foodinspections/FoodInspectionsCurrentFY-en-us.xml");
$importantDate = DataDownloader::getLastModifiedDate("https://bootcamp-coders.cnm.edu/~srexroad/text.txt");

echo "<h2>Businesses:</h2><p>" . $businessesDate->format("Y-m-d H:i:s") . "</p>";
echo "<h2>Inspections:</h2><p>" . $inspectionsDate->format("Y-m-d H:i:s") . "</p>";
echo "<h2>Violations:</h2><p>" . $violationsDate->format("Y-m-d H:i:s") . "</p>";
echo "<h2>XML:</h2><p>" . $xmlDate->format("Y-m-d H:i:s") . "</p>";
echo "<h2>Important:</h2><p>" . $importantDate->format("Y-m-d H:i:s") . "</p>";

// This deletes the files on the bootcamp server
DataDownloader::deleteFiles("/var/lib/abq-data/", "businesses", ".csv");

// This downloads the file to the bootcamp server
DataDownloader::downloadIfNew("http://data.cabq.gov/business/LIVES/businesses.csv", "/var/lib/abq-data/", "businesses", ".csv");

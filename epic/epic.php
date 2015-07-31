<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

		<meta charset="UTF-8">

		<!-- Change header color on Android -->
		<meta name="theme-color" content="#D17B49">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="../css/epic.css">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

		<!-- Google Fonts -->
		<link
			href="https://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700,700italic,100,100italic|Roboto+Condensed:300italic,400italic,700italic,400,300,700"
			rel="stylesheet" type="text/css">

		<!-- lame favicon generated using GIMP -->
		<link type="image/ico" href="../favicon.ico" rel="shortcut icon"/>

		<title>Epic: Documentation</title>
	</0>
	<body class="global">

		<!-- Epic documentation -->
		<header class="title">
			<h1 class="container">
				<img class="logo" src="../images/fork-blue.svg" alt="TruFork Logo"> <em class="color-blue">TruFork</em>
				Documentation: <span class="thin-title">Epic</span>
			</h1>
		</header>

		<!-- System Goals -->
		<div class="full-width padding-top-50">
			<div class="container">
				<h2 class="thin"><i class="fa fa-check-square-o"></i> System Goals:</h2>

				<p>
					A major goal of this project is to provide a way for people to easily find a delicious,
					<em>clean</em>
					place to eat.
				</p>

				<p>
					A secondary goal is for restaurant owners to be able to turn their establishment's cleanliness into
					a
					marketable trait.
				</p>

				<div class="hr"></div>
			</div>
		</div>

		<!-- Personas - K -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-male"></i> Personas:</h2>

				<p>
					<em>Becky, Soccermom</em>
					<br/>
					<em>Age:</em> 32<br/>

					<em>Occupation:</em>
					Becky works as account for a large accounting agency. Her clients include small businesses
					and individuals with a trust fund larger than the total budget for Albuquerque's Public School lunch
					program.<br/>
					<br/>

					<em>Tech:</em> Becky has an Android and a laptop. Most of the communication with her family is
					through Facebook.
					She accesses the web through her phone.<br/>
					<br/>

					<em>Needs and Frustrations:</em>
					Her family is middle class working family. Becky is looking for an easy way to find a restaurant to
					feed her family. She is concerned about quality, location, price, popularity and cleanliness. She
					accesses social
					networking sites daily and values the opinion of others. Becky and her family eat out biweekly on
					the weekends.
					She once got food poisoning from a restaurant that had been downgraded and expects a approval from
					city inspections.<br/>
					<br/>

					<em>Goal:</em> She access TRUfork through the web and makes a decision based on the core data
					provided from
					TRUfork. This ensures that she's getting the best quality, price and atmosphere for her dollar.<br/>
					<br/>

					<em>Micheal Jaxson</em>
					<br/>
					<em>Age:</em> 32<br/>
					<em>Profession:</em> A lawyer who is a well known defense lawyer in Bernalillo county and is a
					senior partner that MJTJ law firm.<br/>
					<br/>

					<em>Tech:</em> Micheal access the web through his iPhone 6 plus and is tech savvy with every iThingy
					that mac dreams of. He wears an iWatch on both his arms.<br/>
					<br/>

					<em>Needs and Frustrations:</em> Micheal works 50 plus hours a week and socially accepted throughout
					the community. He has over 5,000 Facebook friends and SNAP chats every moment of his life.<br/>
					<br/>

					<em>Goals:</em> Micheal is foodie and enjoys eating well received restaurants around Downtown
					Albuquerque.
					He regularly eats eat organic and shops at Whole Foods. He is looking to impress his date with
					inspected approval from the city! He can tell his date that not only did this restaurant receive
					a five star rating and its has passed the Albuquerque City Inspection. Micheal wants to find a five
					star
					restaurant, that has been approved by City.<br/>
					<br/>

					<em>Lynda Teach</em><br/>
					<em>Age:</em> 24<br/>
					<em>Profession:</em> Lynda is a recent graduated from University of New Mexico, with a bachelor of
					Computer Science.
					Lynda is currently seeking employment while working on a website where you can take on-line course
					in the creative field,
					accounting and black belt babysitting.<br/>
					<br/>

					<em>Technology:</em> Lynda is very tech savvy. She is proficient in C++, Java, HTML, MEAN stack,
					and created her own operating system called LYNDAX. She serves on the advisory board for the hack
					group anonymous.<br/>
					<br/>

					<em>Attitudes and Behaviors:</em> Ms. Teach loves anything tech. She attends weekly coding
					networking
					events.
					She has a huge twitter fan base and loves cats without hair.<br/>
					<br/>

					<em>Frustration and needs:</em>
					Lynda needs to find somewhere inexpensive to eat and have a couple of beers with her friends.
					The venue must have a clean bill of health and pass all inspections.
					<br/>

					<em>Goals:</em> Her goal is to find a great, clean place to eat.
				</p>


				<div class="hr"></div>
			</div>
		</div>

		<!-- Use Cases - K -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-suitcase"></i> Use Cases:</h2>
				<br/>
				<em>User accesses TRUfork.com using a web browser</em>
				<br/>

				<em>User has an option to sign in and/or create a user name</em>
				<ul>
					<li>First, Last Name</li>
					<li>Email</li>
					<li>Password</li>
				</ul>

				<em>User taps or clicks on search field<br/>
					The user queries:</em>
				<ul>
					<li>Name</li>
					<li>Address</li>
					<li>Zip</li>
					<li>food type</li>
					<li>phone</li>
					<li>city data approved or disapproved</li>
				</ul>

				<em>User submits query. The search cross references Googles Places API
					and City of Albuquerque Data</em><br/>
				<br/>
				<em>The user sees returned results in the form of TRUfork ratings. 1-5 and a TRUfork approval</em><br/>

				<em>TRUfork approval includes city data and averages GOOGLE data.</em><br/>
				<br/>
				<em>The user has a choice to save the results if signed in or has the option to sign up the same as
					step 1</em><br/>
				<br/>

				<em>The user may click to find out more information</em>
				<ul>
					<li>Map</li>
					<li>Address</li>
					<li>Phone</li>
					<li>Other User Ratings</li>
				</ul>
				<br/>

				<em>They may TRUfork it (like)</em><br/>
				<br/>
				<em> Comment</em><br/>
				<br/>
				<em>Compare field</em>

				<div class="hr"></div>
			</div>
		</div>

		<!-- Conceptual Schema - S -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-cog"></i> Conceptual Schema:</h2>

				<p>
					A user has an ID and a password hash. One user has one profile.
				</p>

				<p>
					A profile has a profile ID, a user ID, and an email. Many profiles can vote, like, and comment on
					many restaurants, and friend many other profiles.

				<p>
					A restaurant has an ID, Google's restaurant ID, Google's rating, ABQ's restaurant ID, ABQ's
					inspection data, our TruFork rating, and an address.
				</p>

				<p>
					A comment has a comment ID, a profile ID, a restaurant ID, a date posted, and the actual comment
					content.
				</p>

				<div class="hr"></div>
			</div>
		</div>

		<!-- ERDs - S -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-sitemap"></i> Entity Relationship Diagram:</h2>

				<img src="../images/trufork_erd.svg" alt="TruFork ERD"/>

				<div class="hr"></div>
			</div>
		</div>

		<!-- System Summary - T -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-terminal"></i> System Summary:</h2>

				<p>
					TruFork is a website that bridges two different databases containing related information. In
					essence,
					TruFork serves as a mechanism to mesh data from Google Places and the City of Albuquerque's ABQ Data
					portal. The end result is to combine Google's restaurant reviews with ABQ Data's restaurant
					inspection
					reports.
				</p>

				<p>
					To create this nexus of data, TruFork will exist initially as a website<sup><a href="#fn1"
																								   id="ref1">1</a></sup>,
					utilizing HTML, CSS, JavaScript, and PHP. TruFork will employ scheduled data-gathering and caching
					algorithms to acquire current Albuquerque restaurant inspection reports. Using the Google Places
					application programming interface (API), TruFork will index restaurant address information against
					the
					database generated from ABQ Data's restaurant inspection reports. In so doing, TruFork will permit
					its
					users to read restaurant reviews <em>and</em> restaurant inspection reports in one location, <em>seamlessly</em>.
				</p>

				<p>
					TruFork will also offer a slim, nominal social media user interface. TruFork's users will be able to
					generate user accounts, generate and read comments, select favorite establishments, and be alerted
					when
					new restaurant inspection reports are made regarding specific restaurants.
				</p>

				<br/> <!-- BrBa -->

				<hr/>
				<!-- Yep, PHPStorm, it's empty. Thanks.-->

				<sup id="fn1">1. Future plans for TruFork include mobile apps across all extant platforms with
					significant
					userbase.<a href="#ref1" title="Jump back to footnote 1 in the text."> <i
							class="fa fa-arrow-circle-o-up"></i></a></sup>

				<div class="hr"></div>
			</div>
		</div>

		<!-- Development Roadmap - T -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-road"></i> Development Roadmap:</h2>

				<p>
					CAUTION: Development is not linear. As such, this roadmap will likely be deviated from, built upon,
					circumvented, folded, spindled, and mutilated.
				</p>

				<br/> <!-- Breakin' 2: Electric Boogaloo-->

				<em>User Interface Features</em>
				<ul>
					<li>Basic, minimalist portal with aesthetically-pleasing graphics</li>
					<li>Update field including new inspection data</li>
					<li>Update field for friend activity</li>
					<li>User log-in field</li>
					<li>Search field</li>
				</ul>

				<br/> <!-- I breaka you face! -->

				<em>Backend Features</em>
				<ul>
					<li>Database culled from weekly download from ABQ Data</li>
					<li>Google Places API</li>
				</ul>

				<br/> <!-- Give us a break with all the breaks, eh?-->

				<em>Social Media Features</em>
				<ul>
					<li>Friend Functionality</li>
					<li>Like Functionality (for liking comments, restaurants, etc...)</li>
					<li>Invite Friends Functionality (for bringing in friends from other sites)</li>
					<li>Symbolic Rewards Functionality</li>
				</ul>

				<br/> <!-- Breaker 1-9 -->

				<em>Future Features</em>
				<ul>
					<li>Mobile Apps for Android and iOS devices</li>
				</ul>

				<div class="hr"></div>
			</div>
		</div>

		<!-- User Stories -->
		<div class="full-width">
			<div class="container">
				<h2 class="thin"><i class="fa fa-users"></i> User Stories:</h2>

				<ul>
					<li>
						I am a user, and I want <em>not</em> to get food poisoning again.
					</li>

					<li>
						I am a restaurant owner, and I want the fact that I have a clean restaurant to get out to
						people.
					</li>

					<li>
						I am a restaurant inspector working for a local government, and I want people in the community
						to take
						some ownership of their dining choices.
					</li>

					<li>
						I am a food columnist/reviewer for a local newspaper, and I want my readers to be able to follow
						up on
						my reviews with their own research.
					</li>

					<li>
						I am a traveling businesswoman, and I want to be able to make quick, but educated decisions
						about
						where to eat when I am on the road.
					</li>
				</ul>
			</div>
		</div>

	</body>
</html>
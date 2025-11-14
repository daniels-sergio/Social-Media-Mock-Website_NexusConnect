# Social-Media-Mock-Website_NexusConnect
This mock website was for my Internet Programming assignment, whereby we were tasked to create a fully functional social media application using PHP, Javascript, HTML and CSS aswell.



XAMPP server was required to run the MySQL and Apache servers and all the database including all the necessary tables were managed through phpMyAdmin



How to run:

1) Install XAMPP if you dont already have it making sure to include the installation packages of both Apache and MySQL in the wizard, this is crucial - https://share.google/7c6Orqr3omydjcvVG
2) Place the folder inside your htdocs folder within the directory of XAMMP. Will goes as follows xammp -> htdocs
3) Then inside XAMPP Run Apache and MySQL, it should look as follows <img width="816" height="499" alt="image" src="https://github.com/user-attachments/assets/4488b0a6-7bd1-4071-a3ee-c0ae7a8ecd70" />

4) Click the admin button of MySQL and create a database named 'nexusconnect' then run the SQL code found in database.sql, this creates the necessary tables which are used for the social media platform
5) From here you can run the website

HOW TO GET STARTED

Once set up is complete you can start using the app, Run the login page to get started,You will be able to create an account if you do not have one.
Once an account is created you will be able to login and be taken to the main dashboard,from here you can create posts,change your account settings and search for and message other reigstered users.
To test the apps full capabilities, I would suggest opening another browser and creating and logging into and account there,from there you can see your original accounts posts and message them.

I did not use websockets as it was not apart of the assigments scope therefore instant loading of the messages is not functional, with that being said you have to refresh the page each time you recieve a message for it to display. The purpose of this was to demonstrate our knowledge of reading and writing to databases.


To view other users profiles and their posts you can search for them in the search bar of the dashboard or click on the profile photo on their posts. This willl take you to their profile whereby you can click to message them and see their specific posts. If you try to do this to yor own profile you will be taken to a similar looking page but this time it will not give you an option to message but rather edit profile. The purpose of this was to demonstrate our understanding of one to one relationships in PHP.

When you make a post it gets posted to the dashboard where other users are able to see it.

Login page
<img width="1860" height="886" alt="image" src="https://github.com/user-attachments/assets/88317d66-9606-4682-b3d8-528e16ed6356" />

Index page (dasboard)
<img width="1867" height="878" alt="image" src="https://github.com/user-attachments/assets/34039bb7-3468-46e9-a1e8-dbe928993d38" />

Settings
<img width="475" height="786" alt="image" src="https://github.com/user-attachments/assets/ced873ee-4434-4599-b6da-93d478392df8" />

Vieweing a users page
<img width="1841" height="879" alt="image" src="https://github.com/user-attachments/assets/f4635919-b080-44d3-b054-c95f17fb1a58" />

Own profile - with no posts
<img width="1857" height="872" alt="image" src="https://github.com/user-attachments/assets/5bb10ed9-46af-4d02-b148-8a934622c1b2" />

Own profile - with a new post
<img width="1849" height="868" alt="image" src="https://github.com/user-attachments/assets/7ad979e1-b175-430f-b9dc-9e7412979696" />

Message panel with chats we have initiated by searching for a user and messaging them
<img width="1851" height="878" alt="image" src="https://github.com/user-attachments/assets/0ad24884-3a2d-416f-a639-8a0591cf3e21" />

<img width="509" height="853" alt="image" src="https://github.com/user-attachments/assets/74d6f694-eacc-41cc-8709-9fdc5aa339f9" />

loads live search suggestions when typing into search bar
<img width="501" height="785" alt="image" src="https://github.com/user-attachments/assets/cda622a1-d7cd-4535-9311-bbca44aa70d5" />





In account settings there is an option to logout which will terminate your current session leaving you unable to load into the dashboard without logging in/signing up


This social media site lacks alot of real world features for now as I followed the assignments questions guidelines because any additional marks would not have been awarded for any "extra" features.


Database layout:

Users table
<img width="1398" height="387" alt="image" src="https://github.com/user-attachments/assets/0d452024-4ab9-4f54-ba83-ccea3d6b5197" />

Messages table
<img width="1377" height="382" alt="image" src="https://github.com/user-attachments/assets/ef758a54-f4f7-4ce0-9c1a-7713b4eacfe4" />

Posts table
<img width="1342" height="377" alt="image" src="https://github.com/user-attachments/assets/20fc33e6-8745-4f47-afc3-1bf66652850d" />

















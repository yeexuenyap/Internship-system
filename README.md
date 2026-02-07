# Internship-system

How to Start the System
To run the Internship Placement & Tracking System, you need to set up the local server environment using XAMPP. Follow these steps:

1. Launch XAMPP Control Panel
Open the XAMPP Control Panel application.
Click the "Start" button next to Apache (to host the website).
Click the "Start" button next to MySQL (to host the database).
Ensure both modules turn green, indicating they are running successfully.

2. Set Up the Database
Since your system relies on PHP and MySQL, you must import the database structure:
Open your web browser and go to: localhost/phpmyadmin.
Click "New" on the left sidebar to create a new database.
Database Name: Enter the name specified in your code (e.g., internship_system or db_internship).
Click the "Import" tab at the top.
Click "Choose File" and select the .sql file located inside your project folder.
Scroll down and click "Go" to execute the import.

3. Access the Website
Ensure your project folder is located in: C:\xampp\htdocs\[your_folder_name].
Open your browser and type the following URL:

localhost/[your_folder_name]/login.php

**Replace [your_folder_name] with the actual name of your project directory.

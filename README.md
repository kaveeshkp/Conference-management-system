**International Research Conference (IRC) Management System**

A web-based system for managing research conference participants, submissions, and sessions. This system provides separate interfaces for administrators and participants with secure authentication and role-based access control.

**Administrator Features** 

- Secure admin dashboard
- User management system
- Session management and scheduling
- Submission tracking and management
- Announcement management
- Account management 

` `**Participant Features** 

- User registration and login 
- Personal dashboard 
- Paper submission system
- Session viewing and scheduling 
- Profile management 
- Access to announcements

**Prerequisites** 

Before you begin, ensure you have the following installed:

- PHP 7.4 or higher 
- MySQL 5.7 or higher 
- PHPMyAdmin  

Project Structure 

INTERNATIONAL RESEARCH CONFERENCE-SYSTEM 

Project Structure 

1. Open **index.html** (web page starting here) 
- HOME -**Home.html**
- ABOUT- **About.html**
- SCHEDULE & SPEAKERS -**sp&sch.html / sp&sch.js** 
- CONTACT**- Contact.html**
- LOGIN**- log.html** 

  `                 `**log.php** 

2. Create data base-**createdatabase.php**
2. Login-**log.php**
2. Register- **register.html/register.php**
2. Admin dashboard 
1. Dashboard - **admindash.php**
1. Manage Users**- amuser.php** 
1. Manage Sessions-**amsessions.php** 
1. Manage Submissions-**admsubmission.php**
1. Announcements-**admannouncement.php**
1. Logout-**admindashlog.php** 
6. User dashboard 
1. Dashboard -**userdash.php** 
1. My Profile-**uprofile.php**
1. Submissions-**usubmission.php** 
1. Resources-**uresources.php**
1. Feedback-**ufeedback.php** 
7. Assets – 
- css 
- js 
- image 

**Usage** 

1. Admin Access 
   1. Navigate to  log.php  
   1. Login with admin credentials 
   1. Select "Admin" role 
   1. Access dashboard at  admindash.php 
1. Participant Access 
- Register at  register.php  
- Login with credentials 
- Select "Participant" role 
- Access dashboard at  userdash.php 

**Common issues and solutions:**

1. Database Connection Failed
   1. Check database credentials in config.php
   1. Verify MySQL service is running 
   1. Check database user permissions
1. Upload Issues 
   1. Verify upload directory permissions 
   1. Check PHP upload limits  
   1. Verify file types are allowed 
1. Login Issues 
- Clear browser cache and cookies
- Check database connection
- Verify user credentials exist in database

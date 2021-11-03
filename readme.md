### Overview

This site was created in reaction to the original DGA site which was difficult (to me) to navigate. I did send credentials to the group at the time 
but never heard back one way or the other. It was an interesting project as it was the first non-inventory and non-personal site I created 
but still used the techniques from my inventory project.

### Installation

In the database directory are two files. An install file which contains all the tables needed. Make sure you create the dga database before adding 
the tables. And a data file which contains some table data that is needed by default.

I will note that I'm still learning some things so some of this will likely change over time as I get better. :)

### Configuration

In the root of the dga site and in the admin, accounts, login, and login/functions directories is a settings.php file.

The if block is mainly used by me because I have the site installed in a few different locations so I want to make sure the correct 
information is used when starting the site. If it's going to be only on one site, feel free to delete the if statement itself.

Next, add the URL to the appropriate line.

You'll need to add an email contact for the admins, devs, and emergency contacts. This can be a comma delimited list of email addresses.

Finally for access, you'll need to add the password you used to provide access to the mysql database. In the data file, the default is **password** 
which of course you should change.


# README #

## What is this? ##

This is a repository containing the files required for making a DragonFable Private Server.

## Credits & Thanks ##
* MentalBlank
* HellFire
* KimChoJapFan
* Misaki
* The AlphaEvolution Team
* Artix Entertainment
* and the users of NothingIllegal.com, Hobo-Studios.org, AlphaHub.org & Trilliux.me.

## How do I get set up? ##

### Recommended Requirements: ###

* AlphaFable Files by MentalBlank

* Latest version of Adobe Flash Player

* Latest version of IE/Firefox/Chrome

* Roughly 800 MB of HDD Storage

* UwAmp, XAMPP, or A decent hosting website (I recommend HostGator)


### Server Files: ###
* Core Files (No Gamefiles Included): https://bitbucket.org/MentalBlank/alphafable/get/c8d96b62bab4.zip
- OR -
git clone https://MentalBlank@bitbucket.org/MentalBlank/alphafable.git

* KimChoJapFan's Compressed Files (Gamefiles Included): https://mega.nz/#!0QFHESwR!IWnty5cVymhpg0IH2k7bTbAot3Wf0RS0wxuV_QggFG4

The server files requires at least 1.0 GiB of disk space when uncompressed and uses the latest version of UwAmp.

The character generator requires Python 2.7 (2.7.10 recommended)

### Setup: ###

**Step 1:** Download, Install & Launch UwAmp, XAMPP or find a decent hosting website (For this tutorial I will be using UwAmp)

**Step 2:** Click 'www folder' in UwAmp or open C:\UwAmp\www\

**Step 3:** Download AlphaFable private server files to the www folder directory that you just opened

**Step 4:** Press Start in UwAmp and click PHPmyAdmin button or open your web browser and navigate to http://localhost/mysql/ (Default user and password: root) (I recommend changing the password)

**Step 5:** Click 'Databases' tab and create a new database (remember what you name it because we will need it later)

**Step 6:** Open the database using the link on the left sidebar

**Step 7:** Click Import

**Step 8:** Select Database SQL to import and click go

**Step 9:** Open the database again using the link on the left sidebar

**Step 10:** Scroll down to df_settings and open the table, Click Edit and
Change DFSitename to what you want to call your server
Change AdminEmail to your email so you can receive bug reports
Change News to whatever the hell you like...
http://i.imgur.com/Y1DOfhV.png

**Step 11:** Scroll Down and save changes

**Step 12:** Go back to the www folder and open includes/config.php

**Step 13:** Change $mysql_user & $mysql_pass to your PHPMyAdmin Info and change $mysql_name to your database name

**Step 14:** open includes/classes/security.class.php and change "var $key = 'RandomKeyBitchesL0043l1';" to something else (Keep this key private ok?)

**Step 15:** open includes/classes/GameFunctions.class.php and change "var $key = 'RandomKeyBitchesL0043l1';" to the same key in Step 17

**Step 16:** rename game/mb-fileGrab.php to something else eg: df-SecretFileDownloader.php

**Step 17:** Open your web browser and navigate to http://localhost/game/mb-fileGrab.php or whatever you just renamed it to and open each link in a new tab (This will download all missing SWF Files and will most likely use between 200mb and 800mb of data). Leave them open till they are done, Until then you can move to the next step.

**Step 18:** Open your web browser and navigate to http://localhost/game/df-signup.php and create an account

**Step 19:** Open PHPMyAdmin, navigate to df_users and change the information of the account you just made so it matches what's in this pic. (This will give you admin access)
http://i.imgur.com/GTBOCRi.png

**Step 20:** Navigate to http://localhost/game/ and sign in and create a character

**Step 21:** Go back to PHPmyAdmin and navigate to the df_characters table and edit the character you just created

**Step 22:** Change Home town to 14161993 this will give you access to the admin town

**Step 23:** If all the SWF Files are done downloading then you should be good to go.

**Additional Notes:**
Apparently If you're using this with the latest version of XAMPP, you'll face far more problems than UwAmp.
Don't forget to enable the rewrite_module in the Apache Modules tab.
Use this Apache setting for UwAmp: http://i.imgur.com/41TI2RL.png
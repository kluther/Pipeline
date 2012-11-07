PIPELINE
Free, open-source software for crowdsourcing creative projects
http://pipeline.cc.gatech.edu/


REQUIREMENTS:

- Apache HTTP Server
- PHP 5
- MySQL


INSTALLATION:

1. Upload all Pipeline files to your web server.
2. Create a new MySQL database and run the queries in database.sql (located in the root directory).
3. Copy config_sample.php to a new file, config.php, and put it in the root directory.
4. Follow the instructions in config.php to configure your Pipeline instance. Remember to save the file when you're finished.
5. Make Apache the owner of the "upload" folder and its subfolders (chown -R apache ./upload)
6. If your Pipeline instance is in a subfolder of your domain, you may need to modify the ErrorDocument path in .htaccess (located in the root directory).


CREDITS:

The Pipeline team, based at the Georgia Institute of Technology (Georgia Tech), includes:

Kurt Luther - Project Lead
Amy Bruckman - Project Co-Lead
Casey Fiesler - Community Manager
Joe Gonzales - Developer
Boris de Souza - Developer
Kevin Ziegler - Developer
Chris Howse - Developer
National Science Foundation - Financial Support

Pipeline makes use of many open-source software components and media assets, including:

Google Charts and Google Libraries
http://code.google.com/apis/chart/
http://code.google.com/apis/libraries/

jQuery and jQueryUI
http://jquery.com/
http://jqueryui.com/

FFmpeg
http://ffmpeg.org/

Flowplayer
http://flowplayer.org/

Fugue icons by Yusuke Kamiyamane
http://p.yusukekamiyamane.com/

SWFObject
http://code.google.com/p/swfobject/

SWFTools
http://www.swftools.org/


LICENSE:

Pipeline is open-source software released under the GNU General Public License (GPL), version 3.0.
http://www.gnu.org/licenses/gpl-3.0.txt
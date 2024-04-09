<!doctype html>
    <head>
        <title>Tiktok hashtag videos Details</title>
        
        <?php //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>
        
        <script src="jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var url = window.location;
                $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
                $('ul.nav a').filter(function() {
                     return this.href == url;
                }).parent().addClass('active');
            });
        </script>
        <style>
            .button{
                background:aliceblue;
                padding-top: 8px;
                padding-bottom: 8px;
                padding-left: 32px;
                padding-right: 32px;
                border-radius: 8px;
            }
            
            /*Navbar*/
            ul {
              list-style-type: none;
              margin: 0;
              padding: 0;
              overflow: hidden;
              background-color: #333;
            }
            
            li {
              float: left;
            }
            
            li a {
              display: block;
              color: white;
              text-align: center;
              padding: 14px 16px;
              text-decoration: none;
            }
            
            li a:hover {
              background-color: #111;
            }
            
            .active {
                background-color:#111; 
            }

            .center{
                text-align:center;
            }
            
            /**table**/
            table {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            
             td, th {
              border: 1px solid #ddd;
              padding: 8px;
            }
            
             tr:nth-child(even){background-color: #f2f2f2;}
            
             tr:hover {background-color: #ddd;}
            
             th {
              padding-top: 12px;
              padding-bottom: 12px;
              text-align: left;
              background-color: #04AA6D;
              color: white;
            }
            
        </style>
    </head>
    <body>
        
        <ul class="nav navbar-nav">
            <li><a href="https://ziscom.in/projects/test/index.php">Home</a></li>
            <li style="float:right;"><a href="https://ziscom.in/projects/test/logout.php">Logout</a></li>
        </ul>
        
        <!-- verify keyword video posts or video id details. Maybe. -->
        
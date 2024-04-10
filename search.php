        <?php
            include('header.php');
            
            if(isset($_COOKIE['user'])) {
                echo '<br>&nbspWelcome '.$_COOKIE['user'];
            } else {
                $baseURL = 'https://ziscom.in/projects/test/login.php';
                header("Location: $baseURL");
            }
            
            if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['keyword']) && !empty($_GET['keyword'])){
                //get id, keyword
                $id = $_GET['id'];
                $keyword = $_GET['keyword'];
                
                //Create or update 'c_cursor_' file for each page refresh
                if(is_file('files/c_cursor_'.$keyword.'.txt')){
                    $myfile = fopen("files/c_cursor_$keyword.txt", "r");
                    $saved_cursor = fread($myfile,filesize("files/c_cursor_$keyword.txt"));
                    $increment_cursor = $saved_cursor + 30;
                    
                    $myfile = fopen("files/c_cursor_$keyword.txt", "w");
                    $txt = $increment_cursor;
                    fwrite($myfile, $txt);
                    fclose($myfile);
                } else {
                    $myfile = fopen("files/c_cursor_$keyword.txt", "w");
                    $txt = 0;
                    fwrite($myfile, $txt);
                    fclose($myfile);
                    $increment_cursor = 0;
                }
                
                //get max 30 videos data on basis of cursor position
                
                /* Code hidden in reference version */
                
                $response = json_decode($response, true);
                
                //extract each video details
                foreach($response['itemList'] as $data) {
                    
                    /*time zone not set in this method
                    $timestamp = $data['createTime'];
                    $date1 = date('Y-m-d', $timestamp);
                    $now = time(); // or your date as well
                    $create_date = strtotime($date1);
                    $datediff = $now - $create_date;
                    $days = round($datediff / (60 * 60 * 24)); //calculate days passed from createtime
                    */
                    
                    $timestamp = $data['createTime'];
                    $date1 = date('Y-m-d', $timestamp);
                    $create_date = strtotime($date1);
                    
                    date_default_timezone_set("CET");
                    $current_date = date('Y-m-d');
                    $now1 = strtotime($current_date);
                    
                    $datediff = $now1 - $create_date;
                    
                    $days = round($datediff / (60 * 60 * 24)); //calculate days passed from createtime
                    
                    $likes = $data['stats']['diggCount']; //calculate total likes
                    
                    /*
                    5 days = 88likes
                    1 day = 88/5
                    */
                    $average_likes_per_day = round($likes/$days); //calculate likes per day
                    
                    $video_id = $data['video']['id']; //get video id
                    
                    $download_url = $data['video']['downloadAddr']; //get download url
                    
                    //read data from 'c_data_' file
                    $myfile = fopen("files/c_data_$keyword.txt", "r") or die("Unable to open file!");
                    $saved_details = fread($myfile,filesize("files/c_data_$keyword.txt"));
                    $data_array = explode('|', $saved_details);
                    
                    //get all video details from 'c_data_' file
                    $v_ids = array();
                    foreach($data_array as $vid_details){
                        
                        $v_ids[] = explode('"', $vid_details)[0];

                    }
                    
                    //remove any empty fields
                    $v_ids = array_filter($v_ids);

                    //If new video_id, not in 'c_data_' file, then add details to file
                    if (!in_array($video_id, $v_ids, TRUE)) {
                        $data = $video_id.'"'.$likes.'"'.$days.'"'.$average_likes_per_day.'"'.$download_url;
                        $myfile = fopen("files/c_data_$keyword.txt", "a") or die("Unable to open file!");
                        $txt = $data.'|';
                        fwrite($myfile, $txt);
                        fclose($myfile);
                    } else {
                        //echo "Repeated found ".$video_id;
                        //echo '<br>';
                    }

                }
                
                //Get all videos details of 'c_data_'
                $myfile = fopen("files/c_data_$keyword.txt", "r") or die("Unable to open file!");
                $saved_filepaths = fread($myfile,filesize("files/c_data_$keyword.txt"));
                $data_array = explode('|', $saved_filepaths);
                $data_array = array_filter($data_array);
                
                //Sort all videos details on basis of virality score
                $sort_by_virality = array();
                foreach($data_array as $data) {
                    $info = explode('"', $data);
                    $sort_by_virality[$info[3]] = $data;
                }
                krsort($sort_by_virality);
                
                //Get video ids from 'downloaded_' file
                if(is_file('files/downloaded_'.$keyword.'.txt')){
                    
                    $myfile = fopen("files/downloaded_$keyword.txt", "r") or die("Unable to open file!");
                    $downloaded_files = fread($myfile,filesize("files/downloaded_$keyword.txt"));
                    $downloaded_array = explode(',', $downloaded_files);
                    
                    $downloaded_ids = array();
                    foreach($downloaded_array as $str){
                        if($pos = strpos($str, '_')){
                            $str=substr($str, $pos+1);
                        }
                        
                        $ids = explode('_', $str); 

                        $downloaded_ids[] = $ids[0];
                    }
                    $downloaded_ids = array_filter($downloaded_ids);

                }
            
                //Remove video ids from 'c_data_' file which are in 'downloaded_' file
                if(!empty($downloaded_ids)){
                    foreach($sort_by_virality as $k => $names){
                        $ids = explode('"', $names);
                        foreach($downloaded_ids as $duplicates){
                            if($ids[0] == $duplicates) {
                                unset($sort_by_virality[$k]); 
                            }
                        }
                    }
                }
                    
                //Get data from highest virality score video
                $value = reset($sort_by_virality);
                $values = explode('"', $value);
                $viral_v_id = $values[0];
                if(isset($values[3]) && !empty($values[3])){
                    $virality_score = $values[3];
                }

                //Calculate hits made in 'hits_' file
                $myfile = fopen("files/hits_$keyword.txt", "r");
                $count_hits = fread($myfile,filesize("files/hits_$keyword.txt"));
                fclose($myfile);
                
                //If hits less than or equal to 5, increment number in 'hits_' file and refresh page (to get next 30 video details)
                if($count_hits <= 5){
                    
                    $increment_hit = $count_hits + 1;
                    $myfile = fopen("files/hits_$keyword.txt", "w");
                    $txt = $increment_hit;
                    fwrite($myfile, $txt);
                    fclose($myfile);
                    header("Refresh:1");
                    
                //Else, redirect to download and upload file with keyword, viral_v_id and virality_score
                } else {

                    /* Code hidden in reference version */
                    
                    $baseURL = "https://ziscom.in/projects/test/upload-to-dropbox.php";
                    header("Location: $baseURL?keyword=$keyword&viral_v_id=$viral_v_id&virality_score=$virality_score");
                    
                }
                
                //Display c_data_ file details in form of a table
                echo '<div>';
                    echo '<center>';
                        echo "<center><h2>Tiktok <i>keyword: $keyword</i> videos Details</h2></center>";
                        echo '<table>';
                            echo '<tr>';
                                echo '<th>';
                                    echo 'S. No.';
                                echo '</th>';
                                echo '<th>';
                                    echo 'Video ID';
                                echo '</th>';
                                echo '<th>';
                                    echo 'Likes';
                                echo '</th>';
                                echo '<th>';
                                    echo 'Days';
                                echo '</th>';
                                echo '<th>';
                                    echo 'Virality<br>(likes per day)';
                                echo '</th>';
                                /*
                                echo '<th>';
                                    echo 'Send to Dropbox';
                                echo '</th>'; 
                                */
                            echo '</tr>';
                            
                            $x = 0;      
                            foreach($sort_by_virality as $data) {
                                $x = $x + 1;
                                $info = explode('"', $data);
                                echo '<tr>';
                                    echo '<td>';
                                        echo $x;
                                    echo '</td>';
                                    echo '<td>';
                                        echo $info[0];
                                    echo '</td>';
                                    echo '<td>';
                                        echo $info[1];
                                    echo '</td>';
                                    echo '<td>';
                                        echo $info[2];
                                    echo '</td>';
                                    echo '<td>';
                                        echo $info[3];
                                    echo '</td>';
                                    /*
                                    echo '<td>';
                                        echo "<a href='$info[4]'>Upload</a>";
                                    echo '</td>';
                                    */
                                echo '</tr>';
                            }
                        echo '</table>';
                    echo '</center>';
                echo '</div>';
                
                include('footer.php');

            //If id and keyword not received, send back to index.php file
            } else {
                $baseURL = "https://ziscom.in/projects/test/index.php";
                header("Location: $baseURL");
                exit;
            }
            
        ?>


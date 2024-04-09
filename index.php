        <?php
            include('header.php');
            
            if(isset($_COOKIE['user'])) {
                echo '<br>&nbspWelcome '.$_COOKIE['user'];
            } else {
                $baseURL = 'https://ziscom.in/projects/test/login.php';
                header("Location: $baseURL");
            }
            
            if(isset($_GET['keyword']) && !empty($_GET['keyword'])){
            
                //get id from keyword
                $keyword = $_GET['keyword'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.tikapi.io/public/hashtag?name='.$keyword,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => array(
                    'X-API-KEY: XXX_UPDATE_XXX',
                    'accept: application/json'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            
                $response = json_decode($response, true);

                $id = $response['challengeInfo']['challenge']['id'];
            
                //Create empty 'c_data_' file for new keyword
                $myfile = fopen("files/c_data_$keyword.txt", "w");
                $txt = "|";
                fwrite($myfile, $txt);
                fclose($myfile);
                
                //Create empty 'hits_' file for new keyword
                $myfile = fopen("files/hits_$keyword.txt", "w");
                $txt = 1;
                fwrite($myfile, $txt);
                fclose($myfile);

                $baseURL = "https://ziscom.in/projects/test/search.php";

                header("Location: $baseURL?id=$id&keyword=$keyword");
                
                exit;

            } else {
                echo "<div class='center'>";
                    echo "<br><br><br>";
                    echo "<form action='' method='GET'>";
                        echo "Enter keyword:<br><br>";
                        echo "<input type='text' name='keyword'><br><br>";
                        echo "<input type='submit' class='button'>";
                    echo "</form>";
                echo "</div>";
            }
            
            include('footer.php');
        ?>

        
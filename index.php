<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

    <style>

        .folder-type-box {
            display: block;
            margin-left: 12px;
            background-color: #B0F;
            color: #ffffff;
            height: max-content;
            padding: 3px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        .folder-size-box {
            display: block;
            margin-left: auto;
            background-color: #7094FF;
            color: #ffffff;
            height: max-content;
            padding: 3px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        .file-type-box {
            display: block;
            margin-left: 12px;
            background-color: #E91E63;
            color: #ffffff;
            height: max-content;
            padding: 3px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

        .file-size-box {
            display: block;
            margin-left: auto;
            background-color: #F2B500;
            color: #ffffff;
            height: max-content;
            padding: 3px 10px;
            font-size: 12px;
            border-radius: 5px;
        }

    </style>

</head>
<body>

    <?php 
    
    if (isset($_POST["submit"])) {
        $path = $_POST["path"];
        $submit = $_POST["submit"];
        # ------------------
        if ($path != "") {
            if (is_dir($path)) {
                # ------------------
                if ($path[strlen($path) - 1] != "\\" && $path[strlen($path) - 1] != ".") {
                    $path .= "\\";
                }
                $dir = scandir($path);
            }
            # ------------------
            $_GET["pathValue"] = $path;
        }
    }

    ?>
    <form action="" method="POST" class="form-inputs mt-50">
        <div class="group-input">
            <input type="text" name="path" id="path" class="custom-input" 
                <?php 

                # ------------------
                if (isset($_GET["pathValue"])) {
                    $pathValue = $_GET["pathValue"];
                    echo "value='{$pathValue}'";
                } 

                ?>
            >
            <input type="submit" name="submit" id="submitpath" class="bn">
        </div>

    </form>

    <div class="form-inputs mt-40">

        <?php 

        # ------------------ create directory element
        function createDirForm($content, $content2 = "") {
            return "<div class='dp-flex p-6 outer-shadow mt-20 mb-20'><button class='bn buttondir'>$content</button>$content2</div>";
        }

        # ------------------ return size 
        function formatSize($sizeByte) {
            if (is_numeric($sizeByte)) {
                if ($sizeByte >= 1073741824) {
                    $sizeByte = number_format($sizeByte / 1073741824, 2) . " GB";
                } else if ($sizeByte >= 1048576) {
                    $sizeByte = number_format($sizeByte / 1048576, 2) . " MB";
                } else if ($sizeByte >= 1024) {
                    $sizeByte = number_format($sizeByte / 1024, 2) . " KB";
                } else if ($sizeByte > 1) {
                    $sizeByte .= " byte";
                } else {
                    $sizeByte = "0 byte";
                }
                return $sizeByte;
            }
        }
        

        /*function getAllSize($arr) {
            $sizeByte = 0;
            foreach($arr as $object) {
                if (is_file($object)) {
                    echo $object . "  ---FILE----  " . __LINE__ . "<br>" . "<br>";
                    $sizeByte += filesize($object);
                } else if (is_dir($object)) {
                    // echo basename($object) . "<br>";
                    if (basename($object) != "." && basename($object) != "..") {
                        echo $object . "  ---FOLDER----  " . __LINE__ . "<br>" . "<br>";
                        getAllSize(scandir($object . "\\"));
                    }
                }
            }
            return $sizeByte;
        }*/

        # ------------------ return size for directory
        function getDirSize($path) {
            $size = 0;
            $path = realpath($path);
            if ($path !== false && $path != '' && file_exists($path) && basename($path) != "." && basename($path) != "..") {
                $pathArr = scandir($path);
                $i = 0;
                foreach ($pathArr as $item) {
                    $item = $path . "\\" . $item;
                    $pathArr[$i] = $item;
                    $i++;
                }
                // echo "<pre>";
                // print_r($pathArr);
                // echo "</pre>";
                //$size = getAllSize($pathArr);
                foreach($pathArr as $object) {
                    if (is_file($object)) {
                        // echo $object . "  ----F----  " . __LINE__ . "<br>" . "<br>";
                        $size += filesize($object);
                    } else if (is_dir($object)) {
                        // echo basename($object) . "<br>";
                        if (basename($object) != "." && basename($object) != "..") {
                            // echo $object . "  ----D----  " . __LINE__ . "<br>" . "<br>";
                            $size += getDirSize($object . "\\");
                        }
                    }
                }
            }
            return $size;
        }

        # ------------------
        if (isset($_POST["submit"])) {
            if ($path != "") {
                if (is_dir($path)) {
                    $dir = scandir($path);
                    foreach ($dir as $item) {
                        $itemPath = "";
                        $itemPath = $path . $item;
                        $_type = "";
                        $typeDes = "";
                        $sizeDes = 0;
                        # ------------------
                        if (is_file($itemPath)) {
                            $typeDes = strchr(basename($item), ".");
                            $sizeDes = formatSize(filesize($itemPath));
                            if ($typeDes == "" || $typeDes == " ") {$typeDes = "Unknown";}
                            if ($sizeDes == "" || $sizeDes == " ") {$sizeDes = "Unknown";}
                            $_type = "<span class='file-size-box'>$sizeDes</span><span class='file-type-box'>$typeDes</span>";
                        # ------------------
                        } else if (is_dir($itemPath)) {
                            if (basename($itemPath) != "." && basename($itemPath) != "..") {
                                if (count(explode("\\", $itemPath)) > 2) {
                                    $sizeDes = formatSize(getDirSize($itemPath));
                                } else {
                                    $sizeDes = "Unknown";
                                }

                                $_type = "<span class='folder-size-box'>$sizeDes</span><span class='folder-type-box'>Folder</span>";
                            }
                        }
                        echo createDirForm($item, $_type);
                    }
                } else {
                    echo "<i class='color-red'>the directory is not found</i>";
                }
            }
        }
        
        ?>

    </div>


    <script>

        let buttonDir = document.querySelectorAll(".buttondir"),
            inputPath = document.getElementById("path"),
            submitPath = document.getElementById("submitpath");


        console.log(buttonDir);

        buttonDir.forEach(item => {

            item.addEventListener('click', _ => {

                if (item.textContent != "") {

                    // ------------------
                    inputPath.value += item.textContent;

                    if (item.textContent == "." || item.textContent == "..") {

                        arrBackOne = [];

                        // ------------------
                        if (inputPath.value[inputPath.value.length - 1] == ".") {

                            arrBackOne = inputPath.value.split("\\");
                            let pathback = "";

                            // ------------------
                            if (inputPath.value[inputPath.value.length - 1] == "." && inputPath.value[inputPath.value.length - 2] != ".") {

                                // ------------------
                                delete(arrBackOne[arrBackOne.length - 1]);
                                delete(arrBackOne[arrBackOne.length - 2]);

                                arrBackOne.forEach(i => {

                                    // ------------------
                                    pathback += i + "\\";

                                });

                                inputPath.value = pathback;

                            // ------------------
                            } else if (inputPath.value[inputPath.value.length - 1] + inputPath.value[inputPath.value.length - 2] == ".." && inputPath.value[inputPath.value.length - 3] != ".") {
                                
                                // ------------------
                                pathback = arrBackOne[0] + "\\";

                                inputPath.value = pathback;

                            }

                        }

                    }

                }

                // ------------------
                submitPath.click();

            });

        });

    </script>
</body>
</html>

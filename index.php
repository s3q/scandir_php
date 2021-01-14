<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/style.css">

    <style>

        .folder-type-box {
            display: block;
            /*margin-left: 12px;*/
            margin-left: auto;
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

        # ------------------ return size for directory
        /*function getDirSize($path){
            //if (count(explode("\\", $path)) > 2) {
                $bytestotal = 0;
                $path = realpath($path);
                if ($path !== false && $path != '' && file_exists($path)){
                    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                        $bytestotal += filesize($object);
                    }
                }
                return $bytestotal;
            //}
        }*/

        # ------------------ return size 
        function getSize($sizeBit, $ott) {
            if (is_numeric($sizeBit)) {
                if ($sizeBit < 1052197.3931623932) {
                    $sizeBit /= 1028.3333333333333; $ott = " KB";
                } else if ($sizeBit < 1075296469.4980695) {
                    $sizeBit /= 1052197.3931623932; $ott = " MG";
                } else if ($sizeBit < 1075296469498069500) {
                    $sizeBit /= 1075296469.4980695; $ott = " GB";
                }
                return $sizeBit = round($sizeBit, 2) . $ott;
            }
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
                        $sizeDes = "";
                        $ot = "";
                        # ------------------
                        if (is_file($itemPath)) {
                            $typeDes = strchr(basename($item), ".");
                            $sizeDes = getSize(filesize($itemPath), $ot);
                            $_type = "<span class='file-size-box'>$sizeDes</span><span class='file-type-box'>$typeDes</span>";
                        # ------------------
                        } else if (is_dir($itemPath)) {
                            if (basename($itemPath) != "." && basename($itemPath) != "..") {
                                //$sizeDes = getSize(getDirSize($itemPath), $ot);
                                //$_type = "<span class='folder-size-box'>$sizeDes</span><span class='folder-type-box'>Folder</span>";
                                $_type = "<span class='folder-type-box'>Folder</span>";
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

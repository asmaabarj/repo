<?php
include("db.php");


session_start();
$email = $_SESSION['LOGINEMAIL'];
$userQuery = "SELECT user_id FROM users WHERE email = '$email'";
$userResult = mysqli_query($conn, $userQuery);

$userData = mysqli_fetch_assoc($userResult);
$userId = $userData['user_id'];

if (isset($_GET['id_theme'])) {
    $theme_id = $_GET['id_theme'];
} else {
    $theme_id = null;
}
if (isset($_POST["insert"])) {
    $title = $_POST["title"];
    $image = $_POST["image"];
    $description = $_POST["description"];
    $currentDateTime = date('Y-m-d H:i:s');

    $insertArticleQuery = "INSERT INTO article (article_title, content, article_image, created_at, author_id,theme_id)
        VALUES ('$title', '$description', '$image', '$currentDateTime', '$userId','$theme_id')";

    mysqli_query($conn, $insertArticleQuery);

    // Redirect to prevent duplicate submissions
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Add your modal styling here */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .checkbox-container {
            height: 100px; /* Set a fixed height for the container */
            overflow-y: auto; /* Enable vertical scroll if needed */
            border: 1px solid #ccc; /* Add a border for clarity */
            padding: 10px; /* Add padding for spacing */
        }

        /* Optional styles for better appearance */
        label {
            display: block;
            margin-bottom: 8px;
        }
   
    </style>
</head>

<body >
    <?php 
    include("favorite.php");
    ?>
<div class="w-[91%] flex justify-end h-[20vh] items-center">
    
    <button  id="openModal"
        class="flex gap-[5px] text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
        type="button">Add New Article<svg xmlns="http://www.w3.org/2000/svg" height="24" fill="white" viewBox="0 -960 960 960" width="24"><path d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
        
    </button></div>
    <div class="min-h-[100vh] w-[90%] flex flex-wrap justify-evenly m-auto">

    <?php
 $article = "SELECT a.article_title, a.content, a.article_image, DATE_FORMAT(a.created_at, '%Y-%m-%d') as created_at, u.fullname
 FROM article a
 INNER JOIN users u ON a.author_id = u.user_id
            WHERE theme_id = $theme_id 
           ORDER BY a.created_at DESC";


$queryarticle = $conn->query($article);

    if ($queryarticle->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($queryarticle)) {



            ?>
                <div 
                    class="w-[28%] h-[65vh] mb-[3rem] max-w-sm bg-white border  border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <a class="w-[100%] " href="#" >
                        <img class="h-[38vh] w-[100%]  rounded-t-lg" src="../images/<?=$row["article_image"]?>" alt="product image" />
                    </a>
                    <div class="px-5 pb-5">
                        <div class="h-[10vh]" >
                            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                <?php echo $row["article_title"]; ?>
                            </h5>
                        </div>
                        <div class="flex items-center mt-2.5 mb-5">
                            <div class="flex items-center space-x-1 rtl:space-x-reverse">
                                <p>author :<?php echo $row["fullname"]; ?> </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between  h-[20%]">
                            <div class="flex gap-[20px]">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M225.8 468.2l-2.5-2.3L48.1 303.2C17.4 274.7 0 234.7 0 192.8v-3.3c0-70.4 50-130.8 119.2-144C158.6 37.9 198.9 47 231 69.6c9 6.4 17.4 13.8 25 22.3c4.2-4.8 8.7-9.2 13.5-13.3c3.7-3.2 7.5-6.2 11.5-9c0 0 0 0 0 0C313.1 47 353.4 37.9 392.8 45.4C462 58.6 512 119.1 512 189.5v3.3c0 41.9-17.4 81.9-48.1 110.4L288.7 465.9l-2.5 2.3c-8.2 7.6-19 11.9-30.2 11.9s-22-4.2-30.2-11.9zM239.1 145c-.4-.3-.7-.7-1-1.1l-17.8-20c0 0-.1-.1-.1-.1c0 0 0 0 0 0c-23.1-25.9-58-37.7-92-31.2C81.6 101.5 48 142.1 48 189.5v3.3c0 28.5 11.9 55.8 32.8 75.2L256 430.7 431.2 268c20.9-19.4 32.8-46.7 32.8-75.2v-3.3c0-47.3-33.6-88-80.1-96.9c-34-6.5-69 5.4-92 31.2c0 0 0 0-.1 .1s0 0-.1 .1l-17.8 20c-.3 .4-.7 .7-1 1.1c-4.5 4.5-10.6 7-16.9 7s-12.4-2.5-16.9-7z"/></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" height="28" width="28" stroke-width="1"
                                viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.-->
                                <path
                                    d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9c.1-.2 .2-.3 .3-.5z" />
                            </svg>
                            
                            </div>
                            <div class="flex items-center gap-[5px]">
                            <svg xmlns="http://www.w3.org/2000/svg" height="15" width="15" fill="gray" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
                            <p class=" text-gray-600 text-[14px] 	"><?php  echo $row["created_at"]; ?></p>
                        </div>
                        </div>
                    </div>
                </div>
            
            <?php

        }
    }
    ?>
        <div id="myModal" class="modal">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create New Article
                    </h3>
                    <button id="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form class="p-4 md:p-5" method="post">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="title"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                            <input type="text" name="title" id="title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type Article title">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">image</label>
                            <input type="file" name="image" id="price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="tags"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tags</label>
    <div class="checkbox-container">
        <?php
        $tags_query= mysqli_query($conn," SELECT * FROM tags where theme_id = $theme_id ");
        if ($tags_query->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($tags_query)) {
                ?>
        <label><input type="checkbox" name="options[]" value="option1"> <?=$row["tag_name"]?></label>

                <?php
            }
        }
        
        ?>
    </div>
                        </div>
                        <div class="col-span-2">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Article
                                Description</label>
                            <textarea id="description" name="description"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                                placeholder="Write article description here"></textarea>
                        </div>
                    </div>
                    <button type="submit" name="insert"
                        class="text-white inline-flex items-center bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Add new Article
                    </button>
                </form>
            </div>
        </div>
</div>
        <div id="overlay" class="overlay"></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const openButton = document.getElementById("openModal");
                const modal = document.getElementById("myModal");
                const closeButton = document.getElementById("closeModal");
                const overlay = document.getElementById("overlay");

                function openModal() {
                    modal.style.display = "block";
                    overlay.style.display = "block";
                }

                function closeModal() {
                    modal.style.display = "none";
                    overlay.style.display = "none";
                }

                openButton.addEventListener("click", openModal);
                closeButton.addEventListener("click", closeModal);
                overlay.addEventListener("click", closeModal);
            });
        </script>

</body>

</html>
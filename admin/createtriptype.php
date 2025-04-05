<?php
session_start();
include("connection.php"); // DB connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $des_name = mysqli_real_escape_string($conn, $_POST['distination']);
    $des_desc = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (isset($_FILES['des_image']) && $_FILES['des_image']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES['des_image']['name'];
        $img_tmp = $_FILES['des_image']['tmp_name'];
        $img_size = $_FILES['des_image']['size'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed_ext) && $img_size <= 2 * 1024 * 1024) {
            $new_img_name = uniqid("Triptype_", true) . '.' . $img_ext;
            $upload_path = 'uploads/' . $new_img_name;

            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }

            if (move_uploaded_file($img_tmp, $upload_path)) {
                $sql = "INSERT INTO Triptype (des_name, des_description, des_image, status) 
                        VALUES ('$des_name', '$des_desc', '$new_img_name', '$status')";

                if (mysqli_query($conn, $sql)) {
                    $message = "Triptype added successfully!";
                } else {
                    $message = "Error: Failed to insert into database.";
                }
            } else {
                $message = "Error: Failed to move uploaded file.";
            }
        } else {
            $message = "Error: Invalid file type or size. Max 2MB. (jpg, jpeg, png, gif)";
        }
    } else {
        $message = "Error: No image uploaded or upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (!menu.parentElement.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
            });
        });

    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
        <div class="flex h-screen">
        <!-- Sidebar -->
        <?php 
        include("frontend/asidebar.php");
        ?>
        <!-- Main Content -->
        <div class="ml-64 p-6 w-full">
            <div class="bg-white shadow-md rounded-lg p-6">
             <h1 class="text-2xl font-bold text-gray-800 mt-12">Add New Trip Type</h1>
        
                    <form method="POST" enctype="multipart/form-data">
                        <!-- Triptype Name -->
                        <div class="mt-8">
                            <label for="des_name" class="block text-sm font-medium text-gray-700 mb-1">Triptype Name</label>
                            <input type="text" id="des_name" name="des_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Triptype Description -->
                        <div class="mb-4">
                            <label for="des_desc" class="block text-sm font-medium text-gray-700 mb-1">Triptype Description</label>
                            <textarea id="des_description" name="des_description" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <!-- Triptype Image -->
                        <div class="mb-4">
                            <label for="des_image" class="block text-sm font-medium text-gray-700 mb-1">Triptype Image</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="des_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div id="upload-label" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                                    </div>
                                    <input id="des_image" name="des_image" type="file" class="hidden" accept="image/*" />
                                    <div id="image-preview" class="p-2 w-full h-full flex justify-start"></div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select status</option>
                                <option value="active">active</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="md:col-span-2 flex justify-end space-x-4">
                            <button type="submit" class="bg-[#008080] text-white px-4 py-2 rounded">Create </button>
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        </div>
                    </form>
                    <!-- Display error/success messages -->
                    <?php if(isset($_SESSION['message'])): ?>
                <div class="fixed top-4 right-4 z-50">
                    <div class="<?= strpos($_SESSION['message'], 'Error') === 0 ? 'bg-red-50 border-red-400 text-red-700' : 'bg-green-50 border-green-400 text-green-700' ?> rounded border px-4 py-3 mb-4 transition-all duration-300 transform hover:scale-[1.02] shadow-lg" role="alert">
                        <div class="flex items-center">
                            <div class="py-1">
                                <?php if(strpos($_SESSION['message'], 'Error') === 0): ?>
                                    <svg class="w-6 h-6 mr-2 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6 mr-2 text-green-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium"><?= htmlspecialchars($_SESSION['message']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                unset($_SESSION['message']);
                endif; 
                ?>
                </div>
            </div>
        </div>
    </div>
<script>
// Image preview functionality
    document.getElementById('des_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const uploadLabel = document.getElementById('upload-label');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="max-h-32 w-auto object-cover rounded-lg">`;
                uploadLabel.classList.add('hidden');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
            uploadLabel.classList.remove('hidden');
        }
    });
</script>

</body>
</html>

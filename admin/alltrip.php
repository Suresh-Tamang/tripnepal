<?php
require '../connection.php';

// session_start();
// if (!isset($_SESSION['admin_id'])) {
//   header('location:adminlogin.php');
//   exit;
// }

$stmt = $conn->prepare("SELECT * FROM trips");
$stmt->execute();
$result = $stmt->get_result();
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
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (event) {
                    event.preventDefault();

                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== this.nextElementSibling) {
                            menu.classList.add('hidden');
                        }
                    });

                    // Toggle current dropdown
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('hidden');
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                if (!event.target.closest('.dropdown-toggle')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
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
        <!-- main section -->
        <div class="ml-64 p-6 w-[84%] mx-auto mt-16">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4"><br></h1>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold mb-4">All TRIPS</h2>
                    <form>
                        <!-- Search Bar -->
                        <div class="mt-4 mb-6">
                            <input type="text" id="searchInput" placeholder="Search users..."
                                class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <!-- User Table -->
                        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
                            <table class="min-w-full" id="userTable">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Transportation</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Accomodation</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Maximum Altitude</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Departure from </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Best Season</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">

                                            Trip type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Meals</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Language</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            fitnesslevel</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            GroupSize</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Minimumage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Maximumage</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($trips = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td class="px-6 py-4"><?php echo $trips["title"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["description"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["price"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["transportation"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["accomodation"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["maximumaltitude"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["departurefrom"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["bestseason"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["triptype"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["meals"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["language"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["fitnesslevel"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["groupsize"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["minimumage"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["maximumage"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["location"]; ?></td>
                                                <td class="px-6 py-4"><?php echo $trips["duration"]; ?></td>
                                                <td class="px-6 py-4">
                                                    <button class="text-blue-500 hover:text-blue-700">Edit</button>
                                                    <button
                                                        class="text-red-500 hover:text-red-700 ml-2 delete-btn">Delete</button>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    $stmt->close();
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
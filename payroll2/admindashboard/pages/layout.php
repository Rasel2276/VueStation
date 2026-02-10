<?php 
    // যেহেতু এই ফাইলটি pages ফোল্ডারে আছে, তাই এক ধাপ পিছনে গিয়ে includes কল করতে হবে
    include('../includes/header.php'); 
    include('../includes/sidebar.php'); 
?>

<main class="main-content">
    <h2 style="margin-bottom: 15px; font-size: 22px;">New Page Title</h2>
    
    <div style="background: var(--bg-sidebar); border: 1px solid var(--border-color); padding: 20px; border-radius: 5px;">
        <p>This is your layout content area. You can add tables, forms, or any other data here.</p>
    </div>
</main>

<?php include('../includes/footer.php'); ?>
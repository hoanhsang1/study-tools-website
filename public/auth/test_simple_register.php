// public/auth/test_simple_register.php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test Registration - Simple</h2>";

require_once __DIR__ . '/../../app/models/User.php';
use App\Models\User; 

try {
    $userModel = new User();
    
    $testData = [
        'username' => 'test_' . rand(1000, 9999),
        'email' => 'test_' . rand(1000, 9999) . '@test.com',
        'fullname' => 'Test User',
        'password' => 'test123',
        'role' => 'free'
    ];
    
    echo "Test data: " . print_r($testData, true) . "<br>";
    
    $result = $userModel->createUser($testData);
    
    if ($result) {
        echo "<div style='background: green; color: white; padding: 10px;'>";
        echo "✅ SUCCESS! User created.<br>";
        echo "Result: " . print_r($result, true);
        echo "</div>";
    } else {
        echo "<div style='background: red; color: white; padding: 10px;'>";
        echo "❌ FAILED! User not created.";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: red; color: white; padding: 10px;'>";
    echo "❌ EXCEPTION: " . $e->getMessage();
    echo "</div>";
}
?>
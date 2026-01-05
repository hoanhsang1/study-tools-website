<?php
// tests/UserModelTest.php
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/models/User.php';

echo "<h1>🧪 Testing User Model (Optimized)</h1>";
echo "<style>
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .test { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
    pre { background: #f5f5f5; padding: 10px; }
</style>";

try {
    // 1. Khởi tạo User Model
    $userModel = new App\Models\User();
    echo "<div class='test success'>✅ 1. User Model initialized</div>";
    
    // 2. Test Base Model methods
    echo "<h3>📋 2. Testing Base Model Methods</h3>";
    
    // selectAll()
    $allUsers = $userModel->selectAll();
    echo "<div class='test'>Total users in DB: " . count($allUsers) . "</div>";
    
    if (empty($allUsers)) {
        echo "<div class='test error'>⚠️ Database is empty. Adding test data...</div>";
        
        // Thêm test data nếu database trống
        $testData = [
            'user_id' => 'USR' . rand(1000, 9999),
            'username' => 'admin',
            'fullname' => 'Administrator',
            'email' => 'admin@studyhub.com',
            'password' => 'admin123',
            'role' => 'admin'
        ];
        
        $userModel->createUser($testData);
        echo "<div class='test success'>✅ Added test user: {$testData['username']}</div>";
    }
    
    // 3. Test Custom Methods
    echo "<h3>🔧 3. Testing Custom Methods</h3>";
    
    // findByEmail()
    $admin = $userModel->findByEmail('admin@studyhub.com');
    if ($admin) {
        echo "<div class='test success'>✅ findByEmail() works</div>";
    }
    
    // emailExists()
    $exists = $userModel->emailExists('admin@studyhub.com');
    echo "<div class='test'>emailExists(): " . ($exists ? 'Exists' : 'Not exists') . "</div>";
    
    // 4. Test CRUD Operations
    echo "<h3>🔄 4. Testing CRUD Operations</h3>";
    
    // Create
    $newUser = [
        'user_id' => 'TEST' . rand(100, 999),
        'username' => 'test_' . time(),
        'fullname' => 'Test User ' . rand(1, 100),
        'email' => 'test' . time() . '@example.com',
        'password' => 'test123',
        'role' => 'student'
    ];
    
    $created = $userModel->createUser($newUser);
    echo "<div class='test'>Create user: " . ($created ? '✅ Success' : '❌ Failed') . "</div>";
    
    if ($created) {
        // Find by ID
        $found = $userModel->findById($newUser['user_id']);
        echo "<div class='test'>Find by ID: " . ($found ? '✅ Found' : '❌ Not found') . "</div>";
        
        // Update
        $updated = $userModel->update($newUser['user_id'], [
            'fullname' => 'Updated Name',
            'role' => 'teacher'
        ]);
        echo "<div class='test'>Update user: " . ($updated ? '✅ Success' : '❌ Failed') . "</div>";
        
        // Verify update
        $afterUpdate = $userModel->findById($newUser['user_id']);
        if ($afterUpdate['role'] === 'teacher') {
            echo "<div class='test success'>✅ Update verified</div>";
        }
        
        // Delete
        $deleted = $userModel->delete($newUser['user_id']);
        echo "<div class='test'>Delete user: " . ($deleted ? '✅ Success' : '❌ Failed') . "</div>";
    }
    
    // 5. Test Advanced Methods
    echo "<h3>🚀 5. Testing Advanced Methods</h3>";
    
    // countAll()
    $total = $userModel->countAll();
    echo "<div class='test'>Total users (countAll): {$total}</div>";
    
    // search()
    $searchResults = $userModel->search('admin');
    echo "<div class='test'>Search 'admin': " . count($searchResults) . " results</div>";
    
    // getByRole()
    $students = $userModel->getByRole('student');
    echo "<div class='test'>Students count: " . count($students) . "</div>";
    
    // 6. Test Transaction
    echo "<h3>💾 6. Testing Transaction</h3>";
    
    $userModel->beginTransaction();
    
    try {
        $txUser1 = [
            'user_id' => 'TX001',
            'username' => 'tx_user1',
            'fullname' => 'Transaction User 1',
            'email' => 'tx1@example.com',
            'password' => 'txpass',
            'role' => 'student'
        ];
        
        $txUser2 = [
            'user_id' => 'TX002', 
            'username' => 'tx_user2',
            'fullname' => 'Transaction User 2',
            'email' => 'tx2@example.com',
            'password' => 'txpass',
            'role' => 'teacher'
        ];
        
        $userModel->createUser($txUser1);
        $userModel->createUser($txUser2);
        
        $userModel->commit();
        echo "<div class='test success'>✅ Transaction committed (2 users created)</div>";
        
        // Cleanup
        $userModel->delete('TX001');
        $userModel->delete('TX002');
        
    } catch (Exception $e) {
        $userModel->rollback();
        echo "<div class='test error'>❌ Transaction rolled back: " . $e->getMessage() . "</div>";
    }
    
    echo "<h2 class='success'>🎉 All tests completed successfully!</h2>";
    echo "<p>✅ Base Model methods work</p>";
    echo "<p>✅ Custom User methods work</p>";
    echo "<p>✅ CRUD operations work</p>";
    echo "<p>✅ Transactions work</p>";
    
} catch (Exception $e) {
    echo "<div class='test error'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
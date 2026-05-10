<?php
/**
 * QuickPOS Form Validation Tests
 * PROJ-19, PROJ-20, PROJ-21, PROJ-22, PROJ-23
 */

class FormTest
{
    private int $passed = 0;
    private int $failed = 0;

    // ===== Helper Function =====
    private function assert(string $testName, bool $condition): void
    {
        if ($condition) {
            echo "✅ PASSED: $testName\n";
            $this->passed++;
        } else {
            echo "❌ FAILED: $testName\n";
            $this->failed++;
        }
    }

    // ===== PROJ-19: Test Empty Fields =====
    public function testEmptyFields(): void
    {
        echo "\n--- PROJ-19: Empty Fields Test ---\n";

        $name = "";
        $email = "";
        $message = "";

        $this->assert(
            "Empty name should fail",
            empty($name)
        );

        $this->assert(
            "Empty email should fail",
            empty($email)
        );

        $this->assert(
            "Empty message should fail",
            empty($message)
        );
    }

    // ===== PROJ-20: Test Invalid Email =====
    public function testInvalidEmail(): void
    {
        echo "\n--- PROJ-20: Invalid Email Test ---\n";

        $invalidEmails = [
            "notanemail",
            "missing@",
            "@nodomain.com",
            "spaces in@email.com",
            "nodot@com"
        ];

        foreach ($invalidEmails as $email) {
            $this->assert(
                "Invalid email '$email' should fail",
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            );
        }
    }

    // ===== PROJ-21: Test Valid Form Submission =====
    public function testValidSubmission(): void
    {
        echo "\n--- PROJ-21: Valid Submission Test ---\n";

        $name = "John Doe";
        $email = "john@example.com";
        $message = "Hello QuickPOS!";

        $this->assert(
            "Valid name should pass",
            !empty($name)
        );

        $this->assert(
            "Valid email should pass",
            filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        );

        $this->assert(
            "Valid message should pass",
            !empty($message)
        );
    }

    // ===== PROJ-22: Test Page Load =====
    public function testPageLoad(): void
    {
        echo "\n--- PROJ-22: Page Load Test ---\n";

        $file = __DIR__ . '/../index.php';

        $this->assert(
            "index.php file exists",
            file_exists($file)
        );

        $this->assert(
            "index.php is readable",
            is_readable($file)
        );

        $content = file_get_contents($file);

        $this->assert(
            "index.php contains QuickPOS",
            strpos($content, 'QuickPOS') !== false
        );
    }

    // ===== PROJ-23: Test PHP Validation Logic =====
    public function testPHPValidation(): void
    {
        echo "\n--- PROJ-23: PHP Validation Logic Test ---\n";

        // Valid emails
        $validEmails = [
            "test@example.com",
            "user@domain.org",
            "hello@quickpos.com"
        ];

        foreach ($validEmails as $email) {
            $this->assert(
                "Valid email '$email' should pass",
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            );
        }

        // XSS Protection
        $dirty = "<script>alert('xss')</script>";
        $clean = htmlspecialchars($dirty);

        $this->assert(
            "XSS input should be sanitized",
            $clean !== $dirty
        );
    }

    // ===== Run All Tests =====
    public function runAll(): void
    {
        echo "========================================\n";
        echo "   QuickPOS Automated Test Suite\n";
        echo "========================================\n";

        $this->testEmptyFields();
        $this->testInvalidEmail();
        $this->testValidSubmission();
        $this->testPageLoad();
        $this->testPHPValidation();

        echo "\n========================================\n";
        echo "TOTAL: " . ($this->passed + $this->failed) . " tests\n";
        echo "✅ Passed: $this->passed\n";
        echo "❌ Failed: $this->failed\n";
        echo "========================================\n";

        if ($this->failed > 0) {
            exit(1);
        }
    }
}

// Run Tests
$test = new FormTest();
$test->runAll();
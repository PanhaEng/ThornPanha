<?php
/**
 * functions.php
 * ប្រមូលផ្តុំ PHP Functions ទាំង ១២ សម្រាប់ប្រូជេក Midterm
 */

// ============================================================
// EX01: Format Full Name
// ============================================================
/**
 * គ្រប់គ្រង និងរៀបចំឈ្មោះពេញ
 * @param string $firstName
 * @param string $lastName
 * @param string $middleName
 * @return string
 */
function formatFullName(string $firstName, string $lastName, string $middleName = ''): string {
    $firstName  = trim(ucwords(strtolower($firstName)));
    $lastName   = trim(ucwords(strtolower($lastName)));
    $middleName = trim(ucwords(strtolower($middleName)));

    if (!empty($middleName)) {
        return "$firstName $middleName $lastName";
    }
    return "$firstName $lastName";
}

function getNameInitials(string $fullName): string {
    $parts = explode(' ', trim($fullName));
    $initials = '';
    foreach ($parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper($part[0]) . '.';
        }
    }
    return $initials;
}

// ============================================================
// EX02: Calculator
// ============================================================
/**
 * ម៉ាស៊ីនគិតលេខ
 * @param float $a
 * @param float $b
 * @param string $op
 * @return array ['result'=>mixed, 'error'=>string|null]
 */
function calculate(float $a, float $b, string $op): array {
    switch ($op) {
        case '+': return ['result' => $a + $b, 'error' => null];
        case '-': return ['result' => $a - $b, 'error' => null];
        case '*': return ['result' => $a * $b, 'error' => null];
        case '/':
            if ($b == 0) return ['result' => null, 'error' => 'មិនអាចចែកនឹងសូន្យបានទេ!'];
            return ['result' => $a / $b, 'error' => null];
        case '%':
            if ($b == 0) return ['result' => null, 'error' => 'មិនអាចគណនា Modulus នឹងសូន្យបានទេ!'];
            return ['result' => fmod($a, $b), 'error' => null];
        case '**': return ['result' => $a ** $b, 'error' => null];
        default: return ['result' => null, 'error' => 'ប្រតិបត្តិករមិនត្រឹមត្រូវ!'];
    }
}

// ============================================================
// EX03: Temperature Converter
// ============================================================
/**
 * បំប្លែងសីតុណ្ហភាព
 * @param float $value
 * @param string $from  'C'|'F'|'K'
 * @param string $to    'C'|'F'|'K'
 * @return float
 */
function convertTemperature(float $value, string $from, string $to): float {
    // Convert to Celsius first
    $celsius = match($from) {
        'C' => $value,
        'F' => ($value - 32) * 5 / 9,
        'K' => $value - 273.15,
        default => $value,
    };
    // Then to target
    return match($to) {
        'C' => $celsius,
        'F' => ($celsius * 9 / 5) + 32,
        'K' => $celsius + 273.15,
        default => $celsius,
    };
}

function temperatureLabel(string $unit): string {
    return match($unit) {
        'C' => 'Celsius (°C)',
        'F' => 'Fahrenheit (°F)',
        'K' => 'Kelvin (K)',
        default => $unit,
    };
}

// ============================================================
// EX04: Cafe Bill Calculator
// ============================================================
/**
 * គណនាវិក្កយបត្រហាងកាហ្វេ
 * @param array $items  [['name'=>'', 'price'=>0, 'qty'=>0], ...]
 * @param float $discountPct  (0–100)
 * @param float $taxPct       (0–100)
 * @return array
 */
function calculateCafeBill(array $items, float $discountPct = 0, float $taxPct = 10): array {
    $subtotal = 0;
    $lineItems = [];

    foreach ($items as $item) {
        $lineTotal = $item['price'] * $item['qty'];
        $subtotal += $lineTotal;
        $lineItems[] = [
            'name'      => $item['name'],
            'price'     => $item['price'],
            'qty'       => $item['qty'],
            'lineTotal' => $lineTotal,
        ];
    }

    $discountAmt = $subtotal * ($discountPct / 100);
    $afterDiscount = $subtotal - $discountAmt;
    $taxAmt = $afterDiscount * ($taxPct / 100);
    $grandTotal = $afterDiscount + $taxAmt;

    return [
        'lineItems'    => $lineItems,
        'subtotal'     => $subtotal,
        'discountPct'  => $discountPct,
        'discountAmt'  => $discountAmt,
        'afterDiscount'=> $afterDiscount,
        'taxPct'       => $taxPct,
        'taxAmt'       => $taxAmt,
        'grandTotal'   => $grandTotal,
    ];
}

// ============================================================
// EX05: Password Checker
// ============================================================
/**
 * ពិនិត្យភាពខ្លាំងនៃពាក្យសម្ងាត់
 * @param string $password
 * @return array ['score'=>0-5, 'strength'=>string, 'criteria'=>[...], 'color'=>string]
 */
function checkPasswordStrength(string $password): array {
    $criteria = [
        'length'    => strlen($password) >= 8,
        'uppercase' => preg_match('/[A-Z]/', $password),
        'lowercase' => preg_match('/[a-z]/', $password),
        'number'    => preg_match('/[0-9]/', $password),
        'special'   => preg_match('/[\W_]/', $password),
    ];

    $score = array_sum(array_map('intval', $criteria));

    $strength = match(true) {
        $score <= 1 => 'ខ្សោយខ្លាំង',
        $score == 2 => 'ខ្សោយ',
        $score == 3 => 'មធ្យម',
        $score == 4 => 'ខ្លាំង',
        $score == 5 => 'ខ្លាំងខ្លាំង',
        default => 'មិនស្គាល់',
    };

    $color = match(true) {
        $score <= 1 => 'danger',
        $score == 2 => 'warning',
        $score == 3 => 'info',
        $score == 4 => 'primary',
        $score == 5 => 'success',
        default => 'secondary',
    };

    return compact('score', 'strength', 'criteria', 'color');
}

// ============================================================
// EX06: Word Count & Palindrome
// ============================================================
/**
 * រាប់ពាក្យក្នុងអត្ថបទ
 */
function countWords(string $text): int {
    return str_word_count(trim($text));
}

function countChars(string $text): int {
    return strlen($text);
}

function countCharsNoSpace(string $text): int {
    return strlen(str_replace(' ', '', $text));
}

function countSentences(string $text): int {
    $text = trim($text);
    if (empty($text)) return 0;
    preg_match_all('/[.!?]+/', $text, $matches);
    return max(1, count($matches[0]));
}

/**
 * ឆែក Palindrome (មិនគិតអក្ខរាវិរុទ្ធ និង Space)
 */
function isPalindrome(string $str): bool {
    $cleaned = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $str));
    return $cleaned === strrev($cleaned);
}

// ============================================================
// EX07: BMI Calculator
// ============================================================
/**
 * គណនា BMI
 * @param float $weight  kg
 * @param float $height  cm
 * @return array
 */
function calculateBMI(float $weight, float $height): array {
    $heightM = $height / 100;
    $bmi = $weight / ($heightM * $heightM);

    [$category, $color] = match(true) {
        $bmi < 18.5         => ['នៅតិចពេក (Underweight)', 'info'],
        $bmi < 25.0         => ['ធម្មតា (Normal weight)', 'success'],
        $bmi < 30.0         => ['លើសប្រក្រតី (Overweight)', 'warning'],
        default             => ['ធាត់ (Obese)', 'danger'],
    };

    $idealWeightMin = 18.5 * $heightM * $heightM;
    $idealWeightMax = 24.9 * $heightM * $heightM;

    return [
        'bmi'            => round($bmi, 2),
        'category'       => $category,
        'color'          => $color,
        'idealWeightMin' => round($idealWeightMin, 1),
        'idealWeightMax' => round($idealWeightMax, 1),
    ];
}

// ============================================================
// EX08: Gradebook
// ============================================================
/**
 * គណនាពិន្ទុ និងផ្តល់ Grade
 * @param float $score  (0-100)
 * @return array
 */
function getGrade(float $score): array {
    [$grade, $gpa, $remark, $color] = match(true) {
        $score >= 90 => ['A',  4.0, 'ល្អប្រសើរ',   'success'],
        $score >= 80 => ['B+', 3.5, 'ល្អ',          'primary'],
        $score >= 70 => ['B',  3.0, 'ល្អ',          'primary'],
        $score >= 65 => ['C+', 2.5, 'មធ្យម',        'info'],
        $score >= 60 => ['C',  2.0, 'មធ្យម',        'info'],
        $score >= 55 => ['D+', 1.5, 'ទន់ខ្សោយ',    'warning'],
        $score >= 50 => ['D',  1.0, 'ទន់ខ្សោយ',    'warning'],
        default      => ['F',  0.0, 'ធ្លាក់',       'danger'],
    };
    return compact('grade', 'gpa', 'remark', 'color');
}

function calculateClassStats(array $scores): array {
    if (empty($scores)) return [];
    return [
        'count'   => count($scores),
        'avg'     => round(array_sum($scores) / count($scores), 2),
        'max'     => max($scores),
        'min'     => min($scores),
        'pass'    => count(array_filter($scores, fn($s) => $s >= 50)),
        'fail'    => count(array_filter($scores, fn($s) => $s < 50)),
    ];
}

// ============================================================
// EX09: Product Search
// ============================================================
/**
 * ស្វែងរកទំនិញ
 * @param string $keyword
 * @param array  $products
 * @return array
 */
function searchProducts(string $keyword, array $products): array {
    $keyword = strtolower(trim($keyword));
    if (empty($keyword)) return [];

    return array_values(array_filter($products, function($p) use ($keyword) {
        return str_contains(strtolower($p['name']), $keyword)
            || str_contains(strtolower($p['category']), $keyword);
    }));
}

function getDefaultProducts(): array {
    return [
        ['id'=>1,  'name'=>'ទឹកក្រូច',          'category'=>'ភេសជ្ជៈ',   'price'=>1.50,  'stock'=>100],
        ['id'=>2,  'name'=>'កាហ្វេ',              'category'=>'ភេសជ្ជៈ',   'price'=>2.00,  'stock'=>80],
        ['id'=>3,  'name'=>'ទឹកពោត',             'category'=>'ភេសជ្ជៈ',   'price'=>1.25,  'stock'=>60],
        ['id'=>4,  'name'=>'នំបុ័ង',              'category'=>'ចំណី',      'price'=>0.75,  'stock'=>200],
        ['id'=>5,  'name'=>'ស្ករ',                'category'=>'គ្រឿងផ្ទះ','price'=>1.00,  'stock'=>150],
        ['id'=>6,  'name'=>'អំបិល',               'category'=>'គ្រឿងផ្ទះ','price'=>0.50,  'stock'=>300],
        ['id'=>7,  'name'=>'ទឹកដោះគោ',          'category'=>'ភេសជ្ជៈ',   'price'=>1.80,  'stock'=>90],
        ['id'=>8,  'name'=>'ស្ពៃ',               'category'=>'បន្លែ',     'price'=>0.80,  'stock'=>50],
        ['id'=>9,  'name'=>'ការ៉ុត',              'category'=>'បន្លែ',     'price'=>0.60,  'stock'=>70],
        ['id'=>10, 'name'=>'ត្រី',                'category'=>'ត្រី/សាច់', 'price'=>3.50,  'stock'=>40],
        ['id'=>11, 'name'=>'សាច់គោ',             'category'=>'ត្រី/សាច់', 'price'=>5.00,  'stock'=>30],
        ['id'=>12, 'name'=>'ស៊ុតមាន់',           'category'=>'ចំណី',      'price'=>0.25,  'stock'=>500],
    ];
}

// ============================================================
// EX10: Todo List Manager
// ============================================================
/**
 * គ្រប់គ្រង Todo List (ដោយប្រើ Session)
 */
function getTodos(): array {
    return $_SESSION['todos'] ?? [];
}

function addTodo(string $title, string $priority = 'normal'): void {
    $todos = getTodos();
    $todos[] = [
        'id'        => time() . rand(100, 999),
        'title'     => htmlspecialchars(trim($title)),
        'priority'  => $priority,
        'done'      => false,
        'created_at'=> date('Y-m-d H:i:s'),
    ];
    $_SESSION['todos'] = $todos;
}

function toggleTodo(string $id): void {
    $todos = getTodos();
    foreach ($todos as &$todo) {
        if ($todo['id'] == $id) {
            $todo['done'] = !$todo['done'];
            break;
        }
    }
    $_SESSION['todos'] = $todos;
}

function deleteTodo(string $id): void {
    $todos = getTodos();
    $_SESSION['todos'] = array_values(array_filter($todos, fn($t) => $t['id'] != $id));
}

function clearDoneTodos(): void {
    $todos = getTodos();
    $_SESSION['todos'] = array_values(array_filter($todos, fn($t) => !$t['done']));
}

// ============================================================
// EX11: Currency Converter
// ============================================================
/**
 * ប្តូរប្រាក់
 * @param float  $amount
 * @param string $from  'USD'|'KHR'|'THB'|'EUR'
 * @param string $to
 * @return array
 */
function convertCurrency(float $amount, string $from, string $to): array {
    // អត្រាប្តូរប្រាក់ (ទៅ USD)
    $rates = [
        'USD' => 1.0,
        'KHR' => 4100.0,
        'THB' => 35.5,
        'EUR' => 0.92,
        'CNY' => 7.25,
        'VND' => 24500.0,
        'SGD' => 1.35,
        'JPY' => 149.5,
    ];

    if (!isset($rates[$from]) || !isset($rates[$to])) {
        return ['error' => 'រូបិយប័ណ្ណមិនត្រឹមត្រូវ'];
    }

    $inUSD  = $amount / $rates[$from];
    $result = $inUSD * $rates[$to];

    return [
        'amount'   => $amount,
        'from'     => $from,
        'to'       => $to,
        'result'   => $result,
        'rate'     => $rates[$to] / $rates[$from],
        'rates'    => $rates,
        'error'    => null,
    ];
}

function getCurrencySymbol(string $code): string {
    return match($code) {
        'USD' => '$',
        'KHR' => '៛',
        'THB' => '฿',
        'EUR' => '€',
        'CNY' => '¥',
        'VND' => '₫',
        'SGD' => 'S$',
        'JPY' => '¥',
        default => $code,
    };
}

// ============================================================
// EX12: Form Validation (Regex)
// ============================================================
/**
 * ផ្ទៀងផ្ទាត់ទម្រង់ចុះឈ្មោះ
 * @param array $data
 * @return array ['errors'=>[], 'valid'=>bool]
 */
function validateRegistrationForm(array $data): array {
    $errors = [];

    // Name
    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'ឈ្មោះត្រូវតែបញ្ចូល';
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', trim($data['name']))) {
        $errors['name'] = 'ឈ្មោះត្រូវតែជាអក្សរអង់គ្លេស (២-៥០ តួ)';
    }

    // Email
    if (empty(trim($data['email'] ?? ''))) {
        $errors['email'] = 'Email ត្រូវតែបញ្ចូល';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'ទម្រង់ Email មិនត្រឹមត្រូវ';
    }

    // Phone
    if (empty(trim($data['phone'] ?? ''))) {
        $errors['phone'] = 'លេខទូរស័ព្ទត្រូវតែបញ្ចូល';
    } elseif (!preg_match('/^0[0-9]{8,9}$/', preg_replace('/[\s\-]/', '', $data['phone']))) {
        $errors['phone'] = 'លេខទូរស័ព្ទខ្មែរ (ចាប់ពី 0, ៨-៩ ខ្ទង់)';
    }

    // Password
    $pwd = $data['password'] ?? '';
    if (empty($pwd)) {
        $errors['password'] = 'ពាក្យសម្ងាត់ត្រូវតែបញ្ចូល';
    } elseif (strlen($pwd) < 8) {
        $errors['password'] = 'ពាក្យសម្ងាត់ត្រូវការ ≥ ៨ តួ';
    } elseif (!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_])/', $pwd)) {
        $errors['password'] = 'ត្រូវមានអក្សរធំ អក្សរតូច លេខ និងនិមិត្តសញ្ញា';
    }

    // Confirm Password
    if (empty($data['confirm_password'] ?? '')) {
        $errors['confirm_password'] = 'ពាក្យសម្ងាត់បញ្ជាក់ត្រូវតែបញ្ចូល';
    } elseif ($pwd !== ($data['confirm_password'] ?? '')) {
        $errors['confirm_password'] = 'ពាក្យសម្ងាត់មិនដូចគ្នា';
    }

    // Age
    $age = $data['age'] ?? '';
    if ($age === '' || $age === null) {
        $errors['age'] = 'អាយុត្រូវតែបញ្ចូល';
    } elseif (!ctype_digit(strval($age)) || (int)$age < 16 || (int)$age > 100) {
        $errors['age'] = 'អាយុត្រូវតែជាលេខ ១៦-១០០';
    }

    // Gender
    if (!in_array($data['gender'] ?? '', ['male', 'female', 'other'])) {
        $errors['gender'] = 'សូមជ្រើសរើសភេទ';
    }

    // Terms
    if (empty($data['terms'])) {
        $errors['terms'] = 'ត្រូវទទួលស្គាល់លក្ខខណ្ឌ';
    }

    return ['errors' => $errors, 'valid' => empty($errors)];
}

// ============================================================
// UTILITY HELPERS
// ============================================================
function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function old(string $key, array $data, string $default = ''): string {
    return htmlspecialchars($data[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

function formatNumber(float $n, int $decimals = 2): string {
    return number_format($n, $decimals);
}

function formatMoney(float $amount, string $currency = 'USD'): string {
    $sym = getCurrencySymbol($currency);
    return $sym . number_format($amount, 2);
}

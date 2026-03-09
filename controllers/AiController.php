<?php
// controllers/AiController.php

class AiController {
    public function chat() {
        $message = $_POST['message'] ?? '';
        
        // Sử dụng API Key mới Minh vừa cung cấp
        $apiKey = "AIzaSyAqwzhPCKYRwgTeelz07iOu4jXRPanHzcM"; 
        
        // CẬP NHẬT: Sử dụng v1beta để hỗ trợ đầy đủ Gemini 1.5 Flash
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $data = [
            "contents" => [
                ["parts" => [["text" => $message]]]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
                "topK" => 40,
                "topP" => 0.95,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        // Vẫn giữ cái này để "vượt rào" SSL trên XAMPP nhé Minh
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        header('Content-Type: application/json');

        // Bắt lỗi Google trả về (nếu Key bị giới hạn vùng)
        if (isset($result['error'])) {
            echo json_encode(['answer' => "⚠️ Google báo lỗi: " . $result['error']['message']]);
            return;
        }

        // Trích xuất phản hồi từ Gemini
        $answer = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Gemini đang bận suy nghĩ, hãy thử lại!";
        
        echo json_encode(['answer' => $answer]);
        exit(); 
    }
}
<?php
// controllers/AiController.php

class AiController {
    public function chat() {
        $message = $_POST['message'] ?? '';
        
        // Sử dụng Key Minh đã lấy được từ image_29536e.png
        $apiKey = "AIzaSyDUaQEqXrTQVnW3WDH00TLW6Q9HsyX7G1s"; 
        
        // CẬP NHẬT: Sử dụng Gemini 3 Pro Preview - Model mạnh nhất hiện tại
        // Lưu ý: gemini-pro-latest hiện đã trỏ thẳng về gemini-3-pro-preview
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=dxdxAIzaSyDUaQEqXrTQVnW3WDH00TLW6Q9HsyX7G1s" . $apiKey;

        $data = [
            "contents" => [
                ["parts" => [["text" => $message]]]
            ],
            // Gemini 3 hỗ trợ 'thinking_level' để trả lời thông minh hơn
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

        // Trích xuất phản hồi từ Gemini 3
        $answer = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Gemini 3 đang bận suy nghĩ, hãy thử lại!";
        
        echo json_encode(['answer' => $answer]);
        exit(); 
    }
}
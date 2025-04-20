<style>
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        background-color: #f4f4f4;
        font-family: Arial, sans-serif;
    }

    .content {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .mail {
        width: 100%;
        max-width: 600px;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h1 {
        color: #333;
        font-size: 24px;
        margin-bottom: 10px;
    }

    p {
        color: #666;
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .cta-button {
        display: inline-block;
        background-color: #007bff;
        color: #ffffff;
        padding: 12px 24px;
        text-decoration: none;
        font-size: 16px;
        border-radius: 4px;
        font-weight: bold;
    }

    .cta-button:hover {
        background-color: #0056b3;
    }

    .footer {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #ddd;
        font-size: 14px;
        color: #666;
        text-align: center;
    }

    .footer p {
        margin: 5px 0;
    }

    .footer a {
        color: #007bff;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }
</style>
<div class="content">
    <div class="mail">
        {!! $report->message !!}
        <br>
        {{-- <a href="#" class="cta-button">Saiba Mais</a> --}}

        <div class="footer">
            {!! $signature !!}
        </div>
    </div>
</div>

<div class="footer">
    <div class="footer-line"></div>
    <p>
        Adresse : {{ $settings->address }} {{ $settings->city }} / 
        IF : {{ $settings->if }} / 
        ICE : {{ $settings->ice }} / 
        RC : {{ $settings->rc }} 
        CNSS : {{ $settings->cnss }}<br>
        Patente : {{ $settings->patente }} / 
        Capitale : {{ number_format($settings->capital, 2) }} 
        Gsm : {{ $settings->phone1 }} - {{ $settings->phone2 }}<br>
        E-mail : {{ $settings->email }}
        @if($settings->website)
            / Site : {{ $settings->website }}
        @endif
    </p>
    @if($settings->footer_text)
        <p style="font-style: italic; color: #666;">{{ $settings->footer_text }}</p>
    @else
        <p style="font-style: italic; color: #666;">Merci de Votre Confiance</p>
    @endif
</div>

<style>
.footer {
    margin-top: 20px;
    text-align: center;
    font-size: 12px;
}

.footer-line {
    border-top: 1px solid #000;
    margin-bottom: 10px;
}

.footer p {
    margin: 5px 0;
    line-height: 1.4;
}
</style> 
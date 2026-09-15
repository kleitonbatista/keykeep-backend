<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    {{-- Substitua a URL abaixo pela URL pública da sua logo ou use texto estilizado --}}
    <h1 style="color: #4F46E5; margin: 0; font-size: 28px;">🔐 KeyKeep</h1>
    <p style="color: #6B7280; font-size: 14px; margin-top: 5px;">Seu Gerenciador de Senhas Seguro</p>
</div>

# Olá!

Este é um e-mail de teste para confirmar a integração do **KeyKeep** com o serviço de disparos.

<x-mail::panel>
**E-mail de destino:** {{ $email }}  
**Status do serviço:** Ativo e Operacional
</x-mail::panel>

<x-mail::button :url="config('app.url')">
Acessar o KeyKeep
</x-mail::button>

Atenciosamente,  
**Equipe KeyKeep**

<x-slot:subcopy>
<div style="text-align: center; color: #9CA3AF; font-size: 12px;">
    <p>© {{ date('Y') }} KeyKeep. Todos os direitos reservados.</p>
    <p>Esta é uma mensagem automática de teste. Por favor, não responda.</p>
</div>
</x-slot:subcopy>
</x-mail::message>
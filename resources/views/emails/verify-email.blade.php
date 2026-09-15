<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <h1 style="color: #4F46E5; margin: 0; font-size: 28px;">🔐 KeyKeep</h1>
    <p style="color: #6B7280; font-size: 14px; margin-top: 5px;">Seu Gerenciador de Senhas Seguro</p>
</div>

# Olá, {{ $user->name }}! 👋

Obrigado por se cadastrar no **KeyKeep**. Para ativar sua conta e liberar o acesso completo ao seu cofre de credenciais, confirme seu endereço de e-mail clicando no botão abaixo:

<x-mail::button :url="$url" color="success">
Confirmar Meu E-mail
</x-mail::button>

<x-mail::panel>
⏱️ **Atenção ao prazo:** Este link de verificação é válido por **60 minutos**. Caso o link expire, você precisará solicitar um novo envio através da aplicação.
</x-mail::panel>

Se você não criou uma conta no KeyKeep, nenhuma ação adicional é necessária.

Atenciosamente,  
**Equipe KeyKeep**

<x-slot:subcopy>
<div style="text-align: center; color: #9CA3AF; font-size: 12px;">
    <p>Se você estiver com problemas para clicar no botão "Confirmar Meu E-mail", copie e cole a URL abaixo no seu navegador:</p>
    <p style="word-break: break-all; color: #4F46E5;">{{ $url }}</p>
    <hr style="border: none; border-top: 1px solid #E5E7EB; margin: 15px 0;">
    <p>© {{ date('Y') }} KeyKeep. Todos os direitos reservados.</p>
</div>
</x-slot:subcopy>
</x-mail::message>
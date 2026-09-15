<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <h1 style="color: #4F46E5; margin: 0; font-size: 28px;">🔐 KeyKeep</h1>
    <p style="color: #6B7280; font-size: 14px; margin-top: 5px;">Seu Gerenciador de Senhas Seguro</p>
</div>

# Olá, {{ $user->name }}! 👋

Recebemos uma solicitação para redefinir a senha da sua conta no **KeyKeep**. Clique no botão abaixo para escolher uma nova senha de acesso:

<x-mail::button :url="$url" color="primary">
Redefinir Minha Senha
</x-mail::button>

<x-mail::panel>
⏱️ **Atenção ao prazo:** Este link de redefinição é válido por **15 minutos**. Se o tempo expirar, você precisará solicitar um novo envio.
</x-mail::panel>

Se você não solicitou a alteração de senha, nenhuma ação é necessária. Sua senha atual permanecerá segura.

Atenciosamente,  
**Equipe KeyKeep**

<x-slot:subcopy>
<div style="text-align: center; color: #9CA3AF; font-size: 12px;">
    <p>Se você estiver com problemas para clicar no botão "Redefinir Minha Senha", copie e cole a URL abaixo no seu navegador:</p>
    <p style="word-break: break-all; color: #4F46E5;">{{ $url }}</p>
    <hr style="border: none; border-top: 1px solid #E5E7EB; margin: 15px 0;">
    <p>© {{ date('Y') }} KeyKeep. Todos os direitos reservados.</p>
</div>
</x-slot:subcopy>
</x-mail::message>
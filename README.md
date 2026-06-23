# Sistema de Orçamentos

Sistema desenvolvido para automação e gestão de propostas comerciais e orçamentos, permitindo o cadastro de clientes, detalhamento de itens e exportação imediata em PDF.

## 🚀 Funcionalidades
- **Gestão de Orçamentos:** Cadastro de propostas com controle de numeração sequencial.
- **Geração de PDF:** Exportação profissional de orçamentos com layout customizável.
- **Customização Comercial:** Campos editáveis de validade, prazo de entrega, condições de pagamento e garantia por proposta.
- **Segurança:** Autenticação de usuários e controle de acesso às páginas administrativas.
- **Interface Responsiva:** Tabela de listagem moderna e organizada com Bootstrap 5.

---

## 📦 Instalação (Passo a Passo)

### 1. Clonando o Projeto
Clone este repositório para a pasta do seu servidor local (ex: `htdocs` do XAMPP):
```bash
git clone [https://github.com/seu-usuario/seu-repositorio.git](https://github.com/seu-usuario/seu-repositorio.git)
```

### 2. Instalando as Dependências (Composer)
Este projeto utiliza a biblioteca Dompdf para gerar os PDFs. Para instalá-la:
Baixe o Composer: Se ainda não tem, baixe em getcomposer.org.
Instale a biblioteca: Abra o terminal (ou Prompt de Comando) dentro da pasta do projeto e execute:
```bash
composer require dompdf/dompdf
```
*Isso criará automaticamente a pasta vendor/ e o arquivo composer.json necessários para o sistema funcionar.*
___

### 3. Configuração do Banco de Dados
1. Importe o arquivo sistema_orcamentos.sql no seu phpMyAdmin.
2. Crie o arquivo db.php na raiz do projeto com suas credenciais:
```php
<?php
$pdo = new PDO('mysql:host=localhost;dbname=sistema_orcamentos', 'root', '');
?>
```
___

### 4. Cadastrando seu Primeiro Usuário
Como o sistema utiliza criptografia de senha, siga estes passos para criar seu acesso:
1. Crie um arquivo chamado hash.php na raiz e cole o código abaixo:
```php
<?php echo password_hash("SUA_SENHA_AQUI", PASSWORD_DEFAULT); ?>
```
2. Acesse http://localhost/seu-projeto/hash.php no navegador.
3. Copie o código gerado na tela.
4. Vá ao seu banco de dados, na tabela usuarios, e cole esse código no campo senha.
5. Delete o arquivo hash.php após o uso por segurança.

## ⚙️ Configuração do Proprietário
1. Informações da Empresa (PDF)
Edite os campos de identificação nos arquivos indicados:

### No arquivo gerar_pdf.php:
- Localize o bloco de "FORNECEDOR" para alterar Nome da Empresa e CNPJ.
- Localize o bloco de "footer" para atualizar seu endereço completo, telefone e slogan.

## 🛠️ Tecnologias Utilizadas
- PHP 8.2+
- MySQL (MariaDB)
- Bootstrap 5 (Interface)
- Dompdf (Geração de PDF)

## 📁 Estrutura de Arquivos
- index.php: Ponto de entrada e redirecionador.
- login.php: Tela de autenticação.
- dashboard.php: Painel administrativo.
- novo_orcamento.php: Interface de criação.
- gerar_pdf.php: Engine de PDF.
- .htaccess: Regras de segurança e URLs amigáveis.

## 📝 Licença
Este sistema foi desenvolvido como uma ferramenta de gestão interna.
Desenvolvido por **Igor Gomes ETI**.
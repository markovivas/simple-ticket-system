=== Simple Ticket System ===
Contributors: (Seu Nome)
Tags: tickets, support, helpdesk, customer support, ticket system
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
License: GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Um sistema de tickets simples e elegante, totalmente integrado ao seu site WordPress. Permite que seus usuários criem e acompanhem chamados de suporte de forma fácil e intuitiva.

== Description ==

O **Simple Ticket System** é a solução perfeita para quem precisa de um sistema de suporte integrado ao WordPress sem complicações. Ele transforma seu site em uma central de ajuda, permitindo que usuários logados abram tickets, descrevam seus problemas, anexem arquivos e acompanhem as respostas dos administradores.

Com um design limpo e responsivo, o plugin se integra perfeitamente ao seu tema. A área de administração é otimizada para que você possa gerenciar todos os chamados de forma eficiente, com status coloridos e informações claras.

**Principais Funcionalidades:**

*   **Formulário de Abertura de Ticket:** Um formulário simples e completo para os usuários enviarem suas solicitações.
*   **Painel "Meus Tickets":** Uma área para o usuário visualizar o histórico e o status de todos os seus chamados.
*   **IDs Personalizados:** Cada ticket recebe um ID único e fácil de referenciar (ex: DTI-001, DTI-002).
*   **Status e Tipos de Solicitação:** Organize os tickets com status (Aberto, Em Andamento, Resolvido) e tipos personalizáveis.
*   **Anexo de Arquivos:** Usuários podem anexar capturas de tela, documentos e outros arquivos ao abrir um ticket.
*   **Sistema de Respostas:** Administradores e usuários podem interagir através de um sistema de respostas integrado à página do ticket.
*   **Notificações por E-mail:** Administradores são notificados sobre novos tickets, e usuários são notificados sobre novas respostas.
*   **Design Responsivo e Moderno:** A interface do usuário é estilizada para ser agradável e funcional em qualquer dispositivo.
*   **Painel de Administração Otimizado:** A lista de tickets no admin possui colunas personalizadas e status coloridos para uma gestão visual e rápida.

== Installation ==

1.  Faça o upload da pasta `simple-ticket-system` para o diretório `/wp-content/plugins/`.
2.  Ative o plugin através do menu 'Plugins' no WordPress.
3.  Crie duas novas páginas no seu WordPress:
    *   Uma página para "Abrir Ticket" e adicione o shortcode: `[ticket_form]`
    *   Uma página para "Meus Tickets" e adicione o shortcode: `[my_tickets]`
4.  Adicione essas páginas ao menu do seu site para que os usuários logados possam acessá-las.

== Usage ==

Após a instalação, o plugin funcionará automaticamente. Os usuários precisam estar logados para criar e visualizar tickets.

**Shortcodes:**

*   `[ticket_form]`
    Exibe o formulário para a criação de um novo ticket. Coloque este shortcode em uma página dedicada, como "Abrir Chamado".

*   `[my_tickets]`
    Exibe uma lista com todos os tickets abertos pelo usuário logado. Coloque este shortcode em uma página como "Meus Chamados".

**Gerenciamento:**

Todos os tickets podem ser gerenciados através do menu "Tickets" no painel de administração do WordPress. Lá, você pode visualizar, responder e alterar o status de cada chamado.

== Screenshots ==

1.  Formulário de criação de ticket no frontend.
2.  Lista de "Meus Tickets" com o layout em cards.
3.  Página de detalhe de um ticket com as respostas.
4.  Lista de tickets no painel de administração com status coloridos.
5.  Página de edição de um ticket no painel de administração.

== Changelog ==

= 1.0.0 =
*   Lançamento inicial do plugin.
*   Criação do Custom Post Type 'ticket'.
*   Implementação das taxonomias 'Status' e 'Tipo de Solicitação'.
*   Criação dos shortcodes `[ticket_form]` e `[my_tickets]`.
*   Adição de sistema de ID personalizado (ex: DTI-001).
*   Implementação de upload de anexos.
*   Desenvolvimento do sistema de respostas (comentários).
*   Criação de notificações por e-mail.
*   Estilização completa do frontend e do painel de administração.

== Frequently Asked Questions ==

= Os usuários precisam estar logados para abrir um ticket? =
Sim. O sistema foi projetado para que apenas usuários autenticados possam criar e gerenciar seus tickets, garantindo a privacidade e a organização.

= Posso personalizar os status e tipos de solicitação? =
Sim. No painel de administração, dentro do menu "Tickets", você encontrará as opções "Status" e "Tipos de Solicitação", onde pode adicionar, editar ou remover os termos conforme sua necessidade.

= Onde os anexos são salvos? =
Os anexos são salvos na Biblioteca de Mídia do WordPress, assim como qualquer outro arquivo de mídia enviado para o site. Eles ficam vinculados ao post do respectivo ticket.
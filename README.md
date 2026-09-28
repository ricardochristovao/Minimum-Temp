# 🚀 Minimum Temp - Template WordPress de Alta Performance 🚀

## Versão ![GitHub release (latest by date)](https://img.shields.io/github/v/release/ricardochristovao/Minimum-Temp?style=flat-square)

Bem-vindo ao **Minimum Temp**, o template WordPress mais leve do mundo para Elementor e landing pages! Criado por Ricardo Christovão da Silva (Ricti), este tema foi projetado com uma filosofia simples: ser invisível. Ele não adiciona nenhum CSS ou JS próprio no front-end, garantindo que suas landing pages carreguem apenas o que o Elementor gerar — nada mais, nada menos.

### ✨ Características Principais

- **🏎️ Performance Máxima**: Zero CSS/JS próprio no front, favicon de 232 bytes, critical CSS inline e remoção agressiva de bloatware do WordPress.
- **🛠️ Otimizado para Elementor**: Compatibilidade total com Elementor, incluindo template Canvas nativo, preconnect para CDN e proteção contra quebras no editor/preview.
- **🧹 Sem Lixo**: Remove emojis, feeds, XML-RPC, query strings, Gutenberg blocks CSS e meta tags desnecessárias automaticamente.
- **🔌 Compatível com WP Rocket**: Detecta WP Rocket e desativa otimizações conflitantes (heartbeat, strip_ver) para evitar problemas em produção.
- **☁️ Integração Cloudflare**: Early Hints automático quando atrás de Cloudflare, acelerando o TTFB sem configuração manual.
- **🖼️ Lazy Load Inteligente**: Usa lazy load nativo do WordPress com fetchpriority="high" na primeira imagem above-the-fold para melhorar o LCP.
- **⚡ jQuery Migrate Condicional**: Remove ~10KB automaticamente apenas quando nenhum plugin ativo depende dele.
- **🎯 Modo LP Pura**: Ative via `define('MINIMUM_TEMP_LP_MODE', true)` no wp-config.php para desativar feeds, comentários e endpoints REST não essenciais.
- **📊 Health Check no Admin**: Painel "MT Performance" no dashboard mostra status do Elementor, WP Rocket, Cloudflare e jQuery Migrate em tempo real.
- **🎨 Fácil Customização**: Tema invisível que não interfere no design das suas landing pages construídas com Elementor.

### 📋 Requisitos

- WordPress 5.6+
- PHP 7.4+
- Plugin Elementor (obrigatório)

### 🚀 Como Usar

1. Baixe o arquivo ZIP mais recente nas [Releases](https://github.com/ricardochristovao/Minimum-Temp/releases).
2. No WordPress, vá em **Aparência > Temas > Adicionar Novo > Enviar Tema** e faça upload do ZIP.
3. Ative o tema e instale/ative o plugin Elementor.
4. Crie sua landing page com Elementor usando o template padrão ou "Elementor Canvas" para páginas sem header/footer.
5. (Opcional) Ative o Modo LP Pura adicionando `define('MINIMUM_TEMP_LP_MODE', true);` no seu `wp-config.php`.

### ⚙️ Configurações Avançadas

#### Modo LP Pura
Adicione no `wp-config.php` para sites dedicados exclusivamente a landing pages:
```php
define('MINIMUM_TEMP_LP_MODE', true);
```
Isso desativa feeds RSS, comentários, pingbacks e endpoints REST não essenciais, reduzindo ainda mais o overhead do WordPress.

#### Template Elementor Canvas
Selecione "Elementor Canvas (Minimum Temp)" como template de página no editor do WordPress para renderizar páginas sem header/footer do tema — ideal para LPs que usam apenas o conteúdo do Elementor.

### 📥 Baixar o Template

Para baixar o template "Minimum Temp", acesse as releases no nosso repositório e baixe o arquivo ZIP mais recente.

[![Baixar Minimum Temp](https://img.shields.io/badge/Baixar-Minimum%20Temp-blue?style=for-the-badge&logo=github)](https://github.com/ricardochristovao/Minimum-Temp/releases)

### 🌟 Conheça o Criador

**Ricardo Christovão da Silva (ByCHR)** — Conecte-se comigo!

[![Instagram](https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white)](https://www.instagram.com/ricardochristovao/)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/ricardochristovao/)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/ricardochristovao)

### 🤝 Contribuições

Sugestões e contribuições são sempre bem-vindas! Sinta-se à vontade para fazer um fork do repositório e enviar suas melhorias via pull request.

### 📜 Licença

Este projeto está sob a licença MIT. Confira [LICENSE](LICENSE) para mais detalhes.

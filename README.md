# SUB FORWARDER

**Subscription Forwarder for Marzban Panel**

This simple PHP script helps users forward their subscription links through another server to avoid detection of the main server by governments or firewalls such as the Great Firewall (GFW). It works by acting as a reverse proxy, allowing users to hide the original server domain while accessing subscription data.

## Features

- Forwards subscription requests to a different domain
- Preserves request headers and body for accurate forwarding
- Follows redirects automatically (limited to 10)
- Supports GET, POST, PUT, PATCH requests
- Adds `X-Forwarded-For` to preserve client IP address
- Helps bypass government restrictions or firewalls

## Usage

1. Clone the repository to your server:

    ```bash
    git clone https://github.com/yourusername/sub-forwarder.git
    ```

2. Open the `index.php` file and update the following line with your target domain:

    ```php
    const TARGET_HOST = 'https://your-new-domain-here.com'; // Write your target host URL here
    ```

3. Create or update the `.htaccess` file in the same directory with the following content:

    ```apache
    RewriteEngine On
    RewriteRule . index.php [L]
    ```

   This ensures that all incoming requests are routed through the `index.php` file.

4. Deploy the script to your web server (e.g., Apache or Nginx).

5. Use the URL of your forwarder server instead of the original subscription link.

## How It Works

- The script intercepts requests to your server and forwards them to the `TARGET_HOST`, maintaining the path and query parameters.
- Request headers and bodies are preserved, ensuring that the forwarded request is identical to the original one.
- By using this technique, users can bypass content filtering and prevent the main server from being detected or blocked by restrictive firewalls.

## Security Considerations

- Ensure SSL verification is enabled in the script (set by default) to prevent man-in-the-middle attacks.
- It is recommended to use this in a secure environment with proper server-side security configurations.

## Disclaimer

This script is intended for educational purposes and for use in legal, ethical contexts. The author is not responsible for any misuse of this tool.

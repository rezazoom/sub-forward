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

### Requirements
- PHP 7.0+ with cURL extension
- Web server with URL rewriting support

## Usage

### For Users with SSH Access (e.g., VPS or Dedicated Server)

1. Clone the repository to your server:

    ```bash
    git clone https://github.com/rezazoom/sub-forward.git
    ```

2. Open the `index.php` file and update the following line with your target domain:

    ```php
    const TARGET_HOST = 'https://your-main-subscription-domain-here.com';
    ```

3. Create or update the `.htaccess` file in the same directory with the following content:

    ```apache
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L,QSA]
    ```

   This ensures that all incoming requests are routed through the `index.php` file.

4. Deploy the script to your web server (e.g., Apache or Nginx).

5. Use the URL of your forwarder server instead of the original subscription link.

---

### For Users on Web Hosts (e.g., cPanel, DirectAdmin)

1. **Download the ZIP file** from the repository:

   - If you're using a hosting service like cPanel or DirectAdmin, you can download the repository as a ZIP file.
   - Go to the repository's page and click on the green "Code" button, then select "Download ZIP."

2. **Upload the ZIP file** to your hosting server:

   - In your hosting panel (cPanel or DirectAdmin), navigate to the **File Manager**.
   - Go to the `public_html` directory (or the relevant web root directory for your site).
   - Upload the ZIP file and extract its contents.

3. **Update the `index.php` file**:

   - In your File Manager, open the `index.php` file and update the following line with your target domain:

    ```php
    const TARGET_HOST = 'https://your-main-subscription-domain-here.com';
    ```

4. **Create or update the `.htaccess` file**:

   - In the same `public_html` directory, create a `.htaccess` file (if it doesn't exist) with the following content:

    ```apache
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L,QSA]
    ```

   This ensures that all requests are routed to the `index.php` file.

5. **Test your setup**:

   - Once everything is uploaded and updated, use the URL of your forwarder server instead of the original subscription link.

---

## How It Works

- The script intercepts requests to your server and forwards them to the `TARGET_HOST`, maintaining the path and query parameters.
- Request headers and bodies are preserved, ensuring that the forwarded request is identical to the original one.
- By using this technique, users can bypass content filtering and prevent the main server from being detected or blocked by restrictive firewalls.

### Error Handling
- All errors are logged to `errors.log` in the same directory
- Includes detailed error context (request method, URI, client IP, timestamp)
- Returns a 500 error with JSON response on failures

### Note on Headers
- Automatically removes 'Host' and 'Accept-Encoding' headers from forwarded requests
- Adds 'X-Forwarded-For' with client IP address
- Filters out certain response headers (Transfer-Encoding, Content-Encoding, Connection)

## Security Considerations
- SSL verification is enabled by default (CURLOPT_SSL_VERIFYPEER)
- Request timeout set to 15 seconds (CURLOPT_TIMEOUT)
- Maximum 10 redirects followed (CURLOPT_MAXREDIRS)
- Recommended to use HTTPS for both TARGET_HOST and forwarder

## Disclaimer

This script is intended for educational purposes and for use in legal, ethical contexts. The author is not responsible for any misuse of this tool.

#### ⚠️ Performance & Architecture Notice

For production-grade deployments, consider replacing this PHP-based forwarder with a dedicated reverse proxy such as NGINX or HAProxy.

PHP is not designed to handle high-performance, concurrent proxy workloads efficiently. While this script is suitable for lightweight or experimental use, serious or large-scale deployments should rely on purpose-built reverse proxy solutions for better performance, stability, and resource utilization.

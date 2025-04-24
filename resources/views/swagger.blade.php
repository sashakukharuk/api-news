<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Swagger UI</title>
  <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css">
</head>
<body>
  <div id="swagger-ui"></div>
  <script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
  <script>
    window.onload = () => {
      SwaggerUIBundle({
        url: "/docs/swagger.yaml",
        dom_id: "#swagger-ui",
        presets: [
          SwaggerUIBundle.presets.apis
        ],
        layout: "BaseLayout"
      });
    };
  </script>
</body>
</html>

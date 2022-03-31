import SwaggerUI from 'swagger-ui'
// or use require if you prefer
// const SwaggerUI = require('swagger-ui')
import 'swagger-ui/dist/swagger-ui.css';

SwaggerUI({
  url: "/api-docs.yml",
  // dom_id: '#swagger-ui',
  // presets: [
  //   SwaggerUIBundle.presets.apis,
  //   SwaggerUIBundle.SwaggerUIStandalonePreset
  // ],
    
  dom_id: '#api-docs',
  // layout: "StandaloneLayout"
})
# Service for Consuming API

This Symfony service is designed to consume an external API to retrieve application-related documents, decode the documents from base64, and store them locally with appropriate naming conventions.

## Features

- **API Consumption**: The service consumes an external API that returns a JSON array with details like app_no, doc_no, ver_no, cert_dt, certificate, and other document-related information.
- **File Handling**: The service extracts the base64-encoded certificate field, decodes it, and stores it locally in a dedicated directory with a filename convention based on the document description and document number.
- **Error Handling**: Proper error handling ensures that any issues with the API request, invalid data, or file storage are logged, and meaningful error messages are returned.

## Prerequisites

- PHP >=8.1
- Symfony 6.4

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/Chirag-Paneliya/educhain-symfony-demo.git
   cd educhain-symfony-demo
   ```

2. Install dependencies:
    ```
    composer install
    ```

3. Environment Configuration:
    ```
    # .env
    API_URL="https://educhain.free.beeceptor.com/get-documents"
    STORAGE_PATH="public/documents"
    ```

## Deployment

1. Build the production version of the app:
    ```
    npm run build
    ```

2. Deploy to your preferred hosting service.

## API Integration

This app fetches the list of applications from the following endpoint:

```
GET https://educhain.free.beeceptor.com/applications
```

Please ensure that the backend API is accessible, or modify the API endpoint if necessary.
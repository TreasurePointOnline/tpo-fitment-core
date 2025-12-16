# Backend API

This is a FastAPI application connecting to a PostgreSQL database.

## Setup

1.  **Environment Variables**: Copy `.env.example` to `.env` and update the values.
2.  **Install Dependencies**:
    ```bash
    pip install -r requirements.txt
    ```
3.  **Run with Docker**:
    ```bash
    docker-compose up --build
    ```
4.  **Run Locally**:
    ```bash
    uvicorn app.main:app --reload
    ```

## API Documentation

Once running, visit:
*   Swagger UI: `http://localhost:8000/docs`
*   ReDoc: `http://localhost:8000/redoc`

## Testing

Run tests with `pytest`.

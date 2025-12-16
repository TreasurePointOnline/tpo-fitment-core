from fastapi import FastAPI, Request, status
from fastapi.responses import JSONResponse
from fastapi.exceptions import RequestValidationError
from .database import engine, Base
from .routers import users, items, auth

# Create database tables
Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="TPO Fitment Core API",
    description="Backend API for TPO Fitment Core",
    version="1.0.0"
)

# Exception Handlers
@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    return JSONResponse(
        status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
        content={"detail": exc.errors(), "body": exc.body},
    )

@app.exception_handler(Exception)
async def general_exception_handler(request: Request, exc: Exception):
    return JSONResponse(
        status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
        content={"detail": "Internal Server Error"},
    )

# Include Routers
app.include_router(auth.router)
app.include_router(users.router)
app.include_router(items.router)

@app.get("/")
def read_root():
    return {"message": "Welcome to TPO Fitment Core API"}

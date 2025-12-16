from typing import List
from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from .. import database, schemas, models, auth

router = APIRouter(
    prefix="/items",
    tags=["Items"]
)

@router.get("/", response_model=List[schemas.Item])
def read_items(skip: int = 0, limit: int = 100, db: Session = Depends(database.get_db), current_user: models.User = Depends(auth.get_current_user)):
    items = db.query(models.Item).offset(skip).limit(limit).all()
    return items

@router.post("/", response_model=schemas.Item)
def create_item_for_user(item: schemas.ItemCreate, db: Session = Depends(database.get_db), current_user: models.User = Depends(auth.get_current_user)):
    db_item = models.Item(**item.dict(), owner_id=current_user.id)
    db.add(db_item)
    db.commit()
    db.refresh(db_item)
    return db_item

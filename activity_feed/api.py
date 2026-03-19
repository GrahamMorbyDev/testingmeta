from fastapi import APIRouter, Depends, HTTPException, Query
from typing import Optional
from sqlalchemy.orm import Session
from . import schemas, crud
from .dependencies import get_db

router = APIRouter(prefix="/activity", tags=["activity_feed"])


@router.post("/events", response_model=schemas.ActivityEventOut)
def post_event(event_in: schemas.ActivityEventCreate, db: Session = Depends(get_db)):
    """Create a new activity event. Intended to be called by internal components.

    This endpoint is intentionally minimal and trusts internal callers. Validation is handled by Pydantic.
    """
    ev = crud.create_event(db, event_in=event_in)
    return ev


@router.get("/teams/{team_id}/feed", response_model=schemas.ActivityFeedPage)
def get_team_feed(
    team_id: int,
    limit: int = Query(50, ge=1, le=200),
    before_id: Optional[int] = Query(None),
    db: Session = Depends(get_db),
):
    """Return a page of recent activity events for a team.

    - Ordered newest -> oldest
    - Use `before_id` (cursor) to page older events. The response `next_cursor` can be passed as `before_id`.
    """
    items, next_cursor = crud.get_recent_events(db, team_id=team_id, limit=limit, before_id=before_id)
    return {"items": items, "next_cursor": next_cursor}

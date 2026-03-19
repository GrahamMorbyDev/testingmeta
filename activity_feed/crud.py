from typing import Optional, Tuple
from sqlalchemy.orm import Session
from sqlalchemy import desc, and_
from .models import ActivityEvent
from .schemas import ActivityEventCreate
from datetime import datetime
import json


def create_event(db: Session, *, event_in: ActivityEventCreate) -> ActivityEvent:
    """Create and persist an ActivityEvent."""
    ev = ActivityEvent(
        team_id=event_in.team_id,
        event_type=event_in.event_type,
        actor_id=event_in.actor_id,
        actor_type=event_in.actor_type,
        target_id=event_in.target_id,
        target_type=event_in.target_type,
        metadata=event_in.metadata or {},
    )
    db.add(ev)
    db.commit()
    db.refresh(ev)
    return ev


def get_recent_events(
    db: Session,
    team_id: int,
    limit: int = 50,
    before_id: Optional[int] = None,
) -> Tuple[list, Optional[int]]:
    """Return recent events for a team, newest first.

    Pagination uses before_id (cursor style). If before_id is provided, events returned will be those
    with id < before_id ordered desc. Returns (items, next_cursor). next_cursor will be the smallest id
    in the returned page, which clients can pass as before_id to fetch older events.
    """
    q = db.query(ActivityEvent).filter(ActivityEvent.team_id == team_id)
    if before_id is not None:
        q = q.filter(ActivityEvent.id < before_id)
    q = q.order_by(desc(ActivityEvent.id)).limit(limit + 1)

    rows = q.all()
    items = rows[:limit]
    next_cursor = None
    if len(rows) > limit:
        # There is at least one more page; cursor is the smallest id of the returned page
        next_cursor = items[-1].id
    return items, next_cursor

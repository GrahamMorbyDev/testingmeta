from datetime import datetime
from typing import Any, Dict, Optional, List
from pydantic import BaseModel


class ActivityEventCreate(BaseModel):
    team_id: int
    event_type: str
    actor_id: Optional[int] = None
    actor_type: Optional[str] = None
    target_id: Optional[int] = None
    target_type: Optional[str] = None
    metadata: Optional[Dict[str, Any]] = None


class ActivityEventOut(BaseModel):
    id: int
    team_id: int
    event_type: str
    actor_id: Optional[int]
    actor_type: Optional[str]
    target_id: Optional[int]
    target_type: Optional[str]
    metadata: Optional[Dict[str, Any]]
    created_at: datetime

    class Config:
        orm_mode = True


class ActivityFeedPage(BaseModel):
    items: List[ActivityEventOut]
    next_cursor: Optional[str] = None

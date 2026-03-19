from datetime import datetime
from sqlalchemy import Column, Integer, String, DateTime, JSON, Index
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()


class ActivityEvent(Base):
    """Represents an activity event for a team.

    The design focuses on being simple and extensible:
    - event_type: short string describing what happened (e.g. "workflow_run.started").
    - actor_* and target_*: optional polymorphic references to the entity that caused the event and
      the entity that the event is about.
    - metadata: free-form JSON for display text, links, counts, etc.
    - created_at: timestamp used for ordering and pagination.
    """

    __tablename__ = "activity_events"

    id = Column(Integer, primary_key=True, autoincrement=True)
    team_id = Column(Integer, nullable=False, index=True)

    event_type = Column(String(64), nullable=False, index=True)  # e.g. workflow_run.completed

    # Polymorphic-ish references (IDs + type strings) so we don't need foreign keys to many tables
    actor_id = Column(Integer, nullable=True)
    actor_type = Column(String(64), nullable=True)

    target_id = Column(Integer, nullable=True)
    target_type = Column(String(64), nullable=True)

    metadata = Column(JSON, nullable=True)  # free-form data for UI

    created_at = Column(DateTime, default=datetime.utcnow, nullable=False, index=True)

    __table_args__ = (Index("ix_activity_team_created", "team_id", "created_at"),)

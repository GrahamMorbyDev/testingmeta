-- Migration: create activity_events table
-- This SQL is compatible with SQLite/Postgres with minor changes. Run with your migration tooling.

CREATE TABLE IF NOT EXISTS activity_events (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  team_id INTEGER NOT NULL,
  event_type VARCHAR(64) NOT NULL,
  actor_id INTEGER,
  actor_type VARCHAR(64),
  target_id INTEGER,
  target_type VARCHAR(64),
  metadata JSON,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE INDEX IF NOT EXISTS ix_activity_team_created ON activity_events(team_id, created_at);
CREATE INDEX IF NOT EXISTS ix_activity_event_type ON activity_events(event_type);

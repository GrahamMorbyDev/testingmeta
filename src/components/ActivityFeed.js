import React, { useEffect, useState, useRef } from 'react';
import { fetchTeamFeed } from '../api/activityApi';
import ActivityEventItem from './ActivityEventItem';
import '../styles/activityFeed.css';

export default function ActivityFeed({ teamId, initialLimit = 20 }) {
  const [events, setEvents] = useState([]);
  const [nextCursor, setNextCursor] = useState(null);
  const [loading, setLoading] = useState(false);
  const [loadingMore, setLoadingMore] = useState(false);
  const [error, setError] = useState(null);

  // track mounted so we avoid state updates after unmount
  const mountedRef = useRef(true);
  useEffect(() => () => { mountedRef.current = false; }, []);

  async function loadInitial() {
    setLoading(true);
    setError(null);
    try {
      const body = await fetchTeamFeed(teamId, { limit: initialLimit });
      if (!mountedRef.current) return;
      setEvents(body.items || []);
      setNextCursor(body.next_cursor ?? null);
    } catch (err) {
      if (!mountedRef.current) return;
      setError(err.message || String(err));
    } finally {
      if (mountedRef.current) setLoading(false);
    }
  }

  async function loadMore() {
    if (!nextCursor) return;
    setLoadingMore(true);
    setError(null);
    try {
      const body = await fetchTeamFeed(teamId, { limit: initialLimit, beforeId: nextCursor });
      if (!mountedRef.current) return;
      // Append older events
      setEvents(prev => [...prev, ...(body.items || [])]);
      setNextCursor(body.next_cursor ?? null);
    } catch (err) {
      if (!mountedRef.current) return;
      setError(err.message || String(err));
    } finally {
      if (mountedRef.current) setLoadingMore(false);
    }
  }

  useEffect(() => {
    if (!teamId) return;
    // Reset when team changes
    setEvents([]);
    setNextCursor(null);
    loadInitial();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [teamId]);

  return (
    <div className="activity-feed">
      <div className="activity-feed-header">Recent activity</div>

      {loading && events.length === 0 ? (
        <div className="activity-feed-loading">Loading…</div>
      ) : null}

      {error ? <div className="activity-feed-error">Error: {error}</div> : null}

      {events.length === 0 && !loading && !error ? (
        <div className="activity-feed-empty">No recent activity.</div>
      ) : null}

      <div className="activity-feed-list">
        {events.map(ev => (
          <ActivityEventItem key={ev.id} event={ev} />
        ))}
      </div>

      <div className="activity-feed-actions">
        {nextCursor ? (
          <button className="btn btn-secondary" onClick={loadMore} disabled={loadingMore}>
            {loadingMore ? 'Loading…' : 'Load older'}
          </button>
        ) : (
          events.length > 0 ? <div className="activity-feed-end">You&apos;re up to date</div> : null
        )}
      </div>
    </div>
  );
}

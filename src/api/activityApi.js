/*
  Simple API helper for the activity feed.
  Respects backend contract from activity_feed/api.py:
    GET /activity/teams/{team_id}/feed?limit=..&before_id=..
  Response: { items: ActivityEventOut[], next_cursor: int | null }
*/

export async function fetchTeamFeed(teamId, { limit = 20, beforeId = null } = {}) {
  const params = new URLSearchParams();
  params.set("limit", String(limit));
  if (beforeId !== null && beforeId !== undefined) params.set("before_id", String(beforeId));

  const url = `/activity/teams/${encodeURIComponent(teamId)}/feed?${params.toString()}`;
  const resp = await fetch(url, {
    method: "GET",
    headers: {
      "Accept": "application/json",
    },
  });

  if (!resp.ok) {
    const text = await resp.text();
    throw new Error(`Failed to fetch activity feed: ${resp.status} ${text}`);
  }

  const body = await resp.json();
  // Expecting shape: { items: [...], next_cursor: number | null }
  return body;
}

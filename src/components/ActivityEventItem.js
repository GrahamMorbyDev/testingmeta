import React from 'react';
import '../styles/activityFeed.css';

function timeAgo(isoDate) {
  if (!isoDate) return '';
  const d = new Date(isoDate);
  const now = new Date();
  const seconds = Math.floor((now - d) / 1000);
  if (seconds < 60) return `${seconds}s`;
  const minutes = Math.floor(seconds / 60);
  if (minutes < 60) return `${minutes}m`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours}h`;
  const days = Math.floor(hours / 24);
  return `${days}d`;
}

function friendlyTextForEvent(event) {
  // Basic mapping for common event types. Keep it concise and human friendly.
  const t = event.event_type || '';
  const meta = event.metadata || {};

  if (t.startsWith('workflow_run.')) {
    const when = t.split('.')[1];
    if (when === 'started') return `Workflow run started${meta.workflow_name ? `: ${meta.workflow_name}` : ''}`;
    if (when === 'completed') return `Workflow run completed${meta.workflow_name ? `: ${meta.workflow_name}` : ''}`;
    if (when === 'failed') return `Workflow run failed${meta.workflow_name ? `: ${meta.workflow_name}` : ''}`;
    if (when === 'requires_fix' || when === 'requires_fixes') return `Workflow run requires fixes${meta.workflow_name ? `: ${meta.workflow_name}` : ''}`;
  }

  if (t.startsWith('agent.')) {
    const sub = t.split('.')[1];
    if (sub === 'created') return `Agent created${meta.agent_name ? `: ${meta.agent_name}` : ''}`;
    if (sub === 'offline') return `Agent offline${meta.agent_name ? `: ${meta.agent_name}` : ''}`;
  }

  if (t.startsWith('workflow.')) {
    const sub = t.split('.')[1];
    if (sub === 'updated') return `Workflow updated${meta.workflow_name ? `: ${meta.workflow_name}` : ''}`;
  }

  // Fallback: show the raw event_type
  return t || 'Activity';
}

function secondaryText(event) {
  const meta = event.metadata || {};
  // Try to show short helpful context: who acted and target name or count
  const parts = [];
  if (event.actor_type) {
    const actor = meta.actor_name || `${event.actor_type}${event.actor_id ? ` #${event.actor_id}` : ''}`;
    parts.push(actor);
  }
  if (event.target_type) {
    const target = meta.target_name || `${event.target_type}${event.target_id ? ` #${event.target_id}` : ''}`;
    parts.push(`on ${target}`);
  }
  if (meta.summary) parts.push(meta.summary);

  return parts.join(' ');
}

export default function ActivityEventItem({ event }) {
  const title = friendlyTextForEvent(event);
  const subtitle = secondaryText(event);
  const time = timeAgo(event.created_at);

  // Determine a simple icon class by event prefix
  const iconClass = (() => {
    if (!event.event_type) return 'icon-generic';
    if (event.event_type.startsWith('workflow_run.')) return 'icon-workflow';
    if (event.event_type.startsWith('agent.')) return 'icon-agent';
    if (event.event_type.startsWith('workflow.')) return 'icon-update';
    return 'icon-generic';
  })();

  return (
    <div className="activity-item">
      <div className={`activity-item-icon ${iconClass}`} aria-hidden />
      <div className="activity-item-body">
        <div className="activity-item-title">{title}</div>
        {subtitle ? <div className="activity-item-subtitle">{subtitle}</div> : null}
      </div>
      <div className="activity-item-time">{time}</div>
    </div>
  );
}

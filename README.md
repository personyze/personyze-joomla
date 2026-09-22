# Personyze for Joomla

Connects a Joomla site to [Personyze](https://www.personyze.com/) — a web personalization,
recommendation and A/B testing platform.

## What this release does

Install the plugin, enter your Personyze account ID, enable it, and the Personyze tag is
added to every page of the site. No template edits, no code.

The tag is not added to the Joomla administrator, and nothing is added until an account ID
is set.

## Requirements

Joomla 4 or 5, PHP 8.1+, and a Personyze account. The account ID is in Personyze under
**Settings → Tracking Code**.

## Installing

Download `plg_system_personyze-1.0.0.zip` from
[Releases](https://github.com/personyze/personyze-joomla/releases), then in Joomla go to
**System → Install → Extensions** and upload it. Enable **System - Personyze** under
**System → Plugins** and set your account ID there.

Updates are delivered through Joomla's own updater; the plugin registers an update site on
install.

## Planned

Content feeds, so Joomla articles can be used for recommendations, and on-page
personalization modules.

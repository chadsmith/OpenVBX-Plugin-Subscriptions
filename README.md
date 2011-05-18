# Subscriptions for OpenVBX

This plugin allows allows for opt-in subscriptions for bulk text and robo-calling. Route flows based on list membership. Use it with the [Match plugin][1] for keyword-based subscriptions. Use it with the [Outbound Flows plugin][2] to be able to schedule updates.

[1]: https://github.com/chadsmith/OpenVBX-Plugin-Match
[2]: https://github.com/chadsmith/OpenVBX-Plugin-Outbound

## Installation

[Download][3] the plugin and extract to /plugins

[3]: https://github.com/chadsmith/OpenVBX-Plugin-Subscriptions/archives/master

## Usage

Once installed, SUBSCRIPTIONS will appear in the OpenVBX sidebar

### Create a new list

1. Click Manage Lists in the OpenVBX sidebar
2. Click Add List
3. Enter a name for your list (this is only seen by you)

### Adding list members from a flow

1. Add the Subscription applet to your Call or SMS flow
2. Select the list
3. Select add

### Removing list members from a flow

1. Add the Subscription applet to your Call or SMS flow
2. Select the list
3. Select remove

### Viewing list members

1. Click Manage Lists in the OpenVBX sidebar
2. Find the list you want to view
3. Click the number of subscribers

### Removing individual list members

1. Click Manage Lists in the OpenVBX sidebar
2. Find the list you want to view
3. Click the number of subscribers
4. Click the trash icon next to the number to remove

### Importing list members

1. Click Manage Lists in the OpenVBX sidebar
2. Click Import
3. Select the list to import to
4. Paste from a CSV or enter one number per line

### Exporting list members

1. Click Manage Lists in the OpenVBX sidebar
2. Click Export
3. Select the list to export

### Sending an SMS update

1. Click Manage Lists in the OpenVBX sidebar
2. Find the list you want to text
3. Click the SMS icon next to the list
4. Select the caller ID (OpenVBX number) to send with
5. Enter the message to text

### Sending a voice update

1. Click Manage Lists in the OpenVBX sidebar
2. Find the list you want to call
3. Click the phone icon next to the list
4. Select the Flow to call with
5. Select the caller ID (OpenVBX number) to call with

### Schedule an outgoing call

1. Click Schedule Update in the OpenVBX sidebar
2. Click Add Call
3. Select the list to update
4. Enter the date and time to call
5. Select the Flow to call with
6. Select the caller ID (OpenVBX number) to call with

### Schedule a text message

1. Click Schedule Update in the OpenVBX sidebar
2. Click Add SMS
3. Select the list to update
4. Enter the date and time to call
5. Select the caller ID (OpenVBX number) to send with
6. Enter the message to text

### Direct flows based on list membership

1. Add the If Member applet to your Call or SMS flow
2. Select the list to check against
3. Drop an applet for if the sender is a member
4. Drop an applet for if the sender is not a member

### Dispatch SMS updates from an SMS flow

1. Add the Dispatch applet to an SMS flow
2. Enter a user or group who is allowed to dispatch updates
3. Select the list to dispatch to
3. (Optional) Drop an applet for if the sender is the dispatcher
4. (Optional) Drop an applet for if the sender is not the dispatcher

## OpenVBX requirements ##

The scheduling portion of this plugin requires my [Outbound Flows plugin][2] and a modified version of OpenVBX to allow for plugin hooks, subpages and cron jobs. Download the modified version from my fork [here][4].

[4]: https://github.com/chadsmith/OpenVBX

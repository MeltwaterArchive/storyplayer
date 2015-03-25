---
layout: v2/using-deprecated
title: appSettings
updated_for_v2: true
---
# appSettings

## What Is Being Removed?

`appSettings` is a config file section. It was added in Storyplayer v2.0-dev. It was marked as deprecated in Storyplayer v2.2.0. Support will be removed in Storyplayer v3.0.

They are being replaced with `storySettings`.

## Why Are appSettings Being Removed?

In Storyplayer v1, `appSettings` came about because we needed somewhere to store settings about the different applications that SPv1 could possibly test. (We didn't have the [system-under-test config file](../configuration/system-under-test-config.html) back then, sadly).

The name `appSettings` has always been confusing for users. When I started revamping the _configuration_ documentation for SPv2, I realised why they are confusing. Their purpose is to be configuration settings for stories to use. The fact that the settings describe applications is neither here nor there.

## How To Migrate

1. Rename all `appSettings` sections in your config files as `storySettings`.
1. Replace all calls to `fromHost()->getAppSetting()` with `fromHost()->getStorySetting()`.
1. Replace all calls to `fromHost()->getAppSettings()` with `fromHost()->getStorySetting()`.
1. Replace all calls to `fromSystemUnderTest()->getAppSetting()` with `fromSystemUnderTest()->getStorySetting()`
1. Replace all calls to `fromSystemUnderTest()->getAppSettings()` with `fromSystemUnderTest()->getStorySetting()`
1. Replace all calls to `fromTestEnvironment()->getAppSetting()` with `fromTestEnvironment()->getStorySetting()`
1. Replace all calls to `fromTestEnvironment()->getAppSettings()` with `fromTestEnvironment()->getStorySetting()`
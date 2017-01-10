<?php $highlight = isset($highlight) ? $highlight : ''; ?>

<div class="wrapper">
    <nav class="tabs tabs--sandwiched">
        <ul class="tabs__list">
            <li class="tabs__item">
                <a href="{{ relroute('student.dashboard.index') }}" class="tabs__link {{ $highlight === 'dashboard' ? 'tabs__link--active' : ''}}">
                    Dashboard
                </a>
            </li>

            <li class="tabs__item">
                <a href="{{ relroute('student.messages.index') }}" class="tabs__link {{ $highlight === 'messages' ? 'tabs__link--active' : ''}}">
                    Messages
                </a>
            </li>

            <li class="tabs__item">
                <a href="{{ relroute('student.account.index') }}" class="tabs__link {{ $highlight === 'account' ? 'tabs__link--active' : ''}}">
                    Account
                </a>
            </li>
        </ul>
    </nav>
</div>

<hr>

<?php
return array(
    'Api\\V1\\Rpc\\QuestionsGet\\Controller' => array(
        'description' => 'Getting the list of questions',
        'GET' => array(
            'response' => '{
   "_limit": "(int),
            Limit for the results quantity per page",
   "_page": "(int) Page of the collection.",
   "_user_fields": "Coma separated names of user fields for question Author.
 Acceptable fields are:

id, login, role, type, sex, avatar, cv_avatar, name, surname, full_name, desired_rank, minimum_salary, salary_currency, visa_usa, visa_usa_exp, visa_shenghen, visa_shenghen_exp, dob, nationality, place_of_residence, home_city, home_adress, contact_mobile, contact_mobile_2, contact_phone, contact_phone_2, english_knowledge, marital_status, user_notes, last_activity, in_db_date, reg_date, readiness_date, company_name, company_description, company_license",
   "_question_fields": "Comma separated field names for question item;
Acceptable fields are:

\'id\' - (int) - question id;
\'user\' - (int) - question author id;
\'title\' - (str) - question title;
\'text\' - (str) - question text;
\'video\' - (str) - link to any video attached to question
\'anonym\' - (bool) (1|0) - is auther posted his question anonymously
\'tags\' - (str) - comma separated list of tags
\'time\' - (int) - unix timestamp of time when question was posted
\'active\' - (bool) - question is visible or not",
   "_stats_fields": "Comma separated list of statistic fields:
Accepted fields are:

\'pics\' - (json array) - data set of attached to question pictures, max 10
\'total_comments\' - (int) - count of comments to the question
\'comments_list\' - (json array) - data set of comments to the question, max 10
\'likes\' - (int) - count of likes
\'likers\' -  (json array) - data set likers basic info (avatar, id, name, login), max 10
\'like_status\' - (bool) - if viewer id sent, returns true if viewer liked this item
\'views\' - (int) - count of views for this question
\'subscribers\' - (int) - count of subscribers to this question
\'subscribe_status\' - (bool) - if viewer id sent, returns true if viewer subscribed to this item
\'answers\' -  (int) - count of answers to this question
\'answered\' -  (bool) - if the question has right answer
\'votes\' - (int) - count of votes
\'vote_status\'   - (bool) - if viewer id sent returns true if viewer voted for this question"
}',
        ),
    ),
    'Api\\V1\\Rpc\\UsersGet\\Controller' => array(
        'description' => 'Getting the list of users',
        'GET' => array(
            'response' => '{"data_list":[{"id":"30107","name":null,"surname":null,"full_name":"George Sestepjorovs","avatar":null,"cv_avatar":null}],"total_results":"19","page":1,"limit":"1"}',
            'description' => 'Request:
- Specify required fields for every user with the comma separated values for the `fields` key. By defaults returns only: id, name, surname, full_name, avatar, cv_avatar.
- Specify `limit` of results per page, default 25;
- Specify page, default 1;
- specify other fields from the fields list to filter the results;

Response:
data_list - collection of requested items;
total_results - total quantity of results;
page - current page;
limit - current limit of each page result;',
        ),
    ),
    'Api\\V1\\Rpc\\QuestionsAdd\\Controller' => array(
        'description' => 'Adding a question',
        'POST' => array(
            'response' => 'If successfull returns id of new question:

{"success":1,"message":"Question added!","generated_id":779}',
            'request' => '{
   "title": "(string), max 255 chars,
Question title",
   "tags": "(string), max 255
Comma separated list of tags for the question",
   "text": "(text),
Text of the question",
   "anonym": "(bool),
Does the author want to hide his name"
}',
        ),
    ),
    'Api\\V1\\Rpc\\LogbooksGet\\Controller' => array(
        'description' => 'Getting the list of blogs',
        'GET' => array(
            'response' => '{
   "_limit": "(int),
                        Limit for the results quantity per page",
   "_page": "(int) Page of the collection.",
   "_user_fields": "Coma separated names of user fields for question Author.",
   "_logbook_fields": "Comma separated field names for logbook item;",
   "_stats_fields": "Comma separated list of statistic fields",
   "id": "(int) 
            Filter by logbook id",
   "owner_id": "(int) 
            Filter by Owner id",
   "query": "(string) 
            Search items which title or tag contains this value"
}',
        ),
    ),
    'Api\\V1\\Rpc\\LogbooksAdd\\Controller' => array(
        'POST' => array(
            'response' => 'If successfull returns id of new logbook:

{"success":1,"message":"Logbook added!","generated_id":779}',
            'request' => '{
   "title": "(string), max 255 chars,
            Logbook title",
   "tags": "(string), max 255
            Comma separated list of tags for the question",
   "text": "(text),
            Text of the question"
}',
        ),
        'description' => 'Adding new logbook entry',
    ),
    'Api\\V1\\Rpc\\NewsGet\\Controller' => array(
        'GET' => array(
            'response' => '{"data_list":[{"post_type":"logbook","id":"37790","title":"","text":" \\u003Ca href=\\u0022http:\\/\\/mail.ru\\/vasya_batareykin\\u0022 target=\\u0022blank\\u0022 \\u003Ehttp:\\/\\/mail.ru\\/vasya_batareykin\\u003C\\/a\\u003E  today another day","video":"","tags":"banana,apple,tomato","user":"37790","time":"1465051902","active":"1","location":null,"rank":null,"salary":null,"salary_unit":null,"ship_name":null,"ship_type":null,"ship_dwt":null,"ship_built":null,"date_join":null,"contract_length":null,"crew_nationality":null,"english":null,"comments":null,"urgent":null,"company_id":null,"subscribers":null,"post_vk":null,"post_vk_id":null,"post_vk_time":null,"anonym":null,"question_id":null,"correct":null,"q_text":null,"user_id":"37790"}],"total_results":"204","_page":"5","_limit":"1"}',
        ),
        'description' => 'Getting News feed',
    ),
    'Api\\V1\\Rpc\\QanswersGet\\Controller' => array(
        'description' => 'Getting list of answers',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Length: 844
Content-Type: application/json; charset=utf-8

{"data_list":[{"answer_id":"2","id":"52","likes":"3","up_votes":"7","down_votes":"1","total_rating":"9","user_id":"52","soc_name":null,"soc_user_id":null,"soc_surname":null,"soc_domain":null,"soc_page":null,"soc_avatar":null,"q_author_id":"37216","q_title":"\\u043f\\u043e\\u0434\\u0432\\u043e\\u0434\\u043d\\u044b\\u0435 \\u043b\\u043e\\u0434\\u043a\\u0438","q_text":"\\u0026lt;p\\u0026gt;\\u041e\\u0442\\u043a\\u0443\\u0434\\u0430 \\u043d\\u0430 \\u043f\\u043e\\u0434\\u0432\\u043e\\u0434\\u043d\\u043e\\u0439 \\u043b\\u043e\\u0434\\u043a\\u0435 \\u0431\\u0435\\u0440\\u0443\\u0442 \\u0432\\u043e\\u0437\\u0434\\u0443\\u0445?\\u0026lt;\\/p\\u0026gt;","q_anonym":"0","q_time":"1455171158","q_tags":"","q_name":"Nadya","q_surname":"Drozdova","q_full_name":"","q_login":"Nadya","q_avatar":"crop_C5mV9eN4jO9pH5a.jpg","q_cv_avatar":"R5gG5yO2pA6wI5o.jpg"}],"total_results":"5","_page":1,"_limit":"1"}',
        ),
    ),
    'Api\\V1\\Rpc\\CommentsGet\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Length: 385
Content-Type: application/json; charset=utf-8

{"data_list":[{"source":"local","comment_id":"52","pics":null,"user_id":"38918","section":null,"section_id":null,"comment":"iam is ready new","up_votes":"1","reply_on":null,"time":null,"id":"38918","down_votes":"0","vote_status":null,"soc_name":null,"soc_user_id":null,"soc_surname":null,"soc_domain":null,"soc_page":null,"soc_avatar":null}],"total_results":"1","_page":1,"_limit":"1"}',
        ),
        'description' => 'Getting comments list',
    ),
    'Api\\V1\\Rpc\\ContactsGet\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"user_id":"33260","id":"33260","worked_together":"1","relations":null}],"total_results":"0","_page":1,"_limit":"1"}',
        ),
        'description' => 'Getting list of user contacts',
    ),
    'Api\\V1\\Rpc\\ContactsRemove\\Controller' => array(
        'description' => 'Canceling friendship (if request was sent it would be removed, if request was received user will be moved to followers)',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Friendship canceled","generated_id":"0"}',
        ),
    ),
    'Api\\V1\\Rpc\\LikesGet\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"source":"local","like_id":"380","id":"38811","soc_page":null,"name":null,"surname":null,"avatar":null,"user_id":"38811"}],"total_results":"4","_page":1,"_limit":"1"}',
        ),
        'description' => 'Getting likes for some item',
    ),
    'Api\\V1\\Rpc\\LikesAdd\\Controller' => array(
        'description' => 'Adding like to some item',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Like added!","generated_id":"934"}',
            'request' => '{
   "section": "(varchar) 
                Article section",
   "section_id": "(int) 
                Article id"
}',
        ),
    ),
    'Api\\V1\\Rpc\\CommentsAdd\\Controller' => array(
        'description' => 'Adding new comment to the article.

Comment with the same text could not be added ofthen than every 30 seconds',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Comment added!","generated_id":"148"}',
            'request' => '{
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id",
   "comment": "(text),
            Comment text"
}',
        ),
    ),
    'Api\\V1\\Rpc\\ContactsAdd\\Controller' => array(
        'description' => 'Send friendship request to some user',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Request sent!","generated_id":"0"}',
            'request' => '{
   "user_id": "(int),
                    User to which friendship request will be sent "
}',
        ),
    ),
    'Api\\V1\\Rpc\\QanswersAdd\\Controller' => array(
        'description' => 'Adding answer for the question',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Length: 47
Content-Type: application/json; charset=utf-8

{"detail":"Answer added!","generated_id":"193"}',
            'request' => '{
   "question_id": "(int) 
                Question id on which you answering",
   "text": "(text),
            Text of the answer",
   "anonym": "(bool), [default=0]
            Does the author want to hide his name"
}',
        ),
    ),
    'Api\\V1\\Rpc\\VacanciesAdd\\Controller' => array(
        'POST' => array(
            'response' => '',
            'description' => '',
            'request' => '{
   "title": "(string), max 255
            Vacancy Title",
   "time": "(int), Unix Time Stamp
            Postpone publication",
   "text": "(text),
            Vacancy description",
   "rank": "(varchar), max 32
            Rank",
   "salary": "(int), max 6
            Salary",
   "salary_unit": "(varchar option), USD/EUR/GBP
            Salary currency",
   "ship_name": "(varchar), 32
            Ship Name",
   "ship_type": "(varchar), 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "ship_dwt": "(int), 10
            Ship Size / Dwt",
   "date_join": "(int), Unix Time
            Date Join",
   "contract_length": "(digits), days 1 - 999
            Contract length",
   "crew_nationality": "(varchar), 32
            Crew nationality",
   "english": "(digits), 1 - 5
            Minimum English Level",
   "comments": "(varchar), 255
            Any personal comments (visible only to vacancy author)",
   "urgent": "(bool), 0/1
                 is vacancy urgent?"
}',
        ),
        'description' => 'Adding new vacancy',
    ),
    'Api\\V1\\Rpc\\LikesRemove\\Controller' => array(
        'description' => 'Removing like from item',
        'POST' => array(
            'request' => '{
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Like removed!"}',
        ),
    ),
    'Api\\V1\\Rpc\\LikesIsLiked\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Like exist","result":true}',
        ),
        'description' => 'Get information is user liked item or not',
    ),
    'Api\\V1\\Rpc\\UserExperienceGet\\Controller' => array(
        'description' => 'Getting user experience, 
if owner_id not provided, will return experience of the current user.
Information returned by this request will be hidden depends on the requesting user access rights.
(friend/company/is user in company database)',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"exp_id":"175324","ship_name":"******","rank":"2nd Officer","user_id":"37790","id":"37790"}],"total_results":"1","_page":1,"_limit":25}',
        ),
    ),
    'Api\\V1\\Rpc\\UserExperienceAdd\\Controller' => array(
        'description' => 'Adding contract to experience of the current user',
        'POST' => array(
            'request' => '{
   "ship_name": "(string), max 32
            Ship Name",
   "date_from": "(int) unix timestamp
                    Date when contract begin",
   "date_to": "(int) unix timestamp
                    Date when contract completed",
   "rank": "(string), max 32
            Rank",
   "ship_type": "(string), max 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "dwt": "(int), 10
            Ship Size / Dwt",
   "grt": "(int), 10
            Ship Size / Gross Tonnage",
   "bhp": "(int), 10
            Engine Horse Powers (Kilowats)",
   "company": "(string), max 255
            Company/Owner/Crewing agency",
   "trading_area": "(string), max 1000 chars
            Trading area, ports visited during contract",
   "text": "(string), max 1000 chars
            Any additional information about this contract"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience added!","generated_id":"175327"}',
        ),
    ),
    'Api\\V1\\Rpc\\UserExperienceRemove\\Controller' => array(
        'description' => 'Remove user experience record',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience Record removed"}',
            'request' => '{
   "id": "(int),
                    Experience record id"
}',
        ),
    ),
    'Api\\V1\\Rpc\\CommentsRemove\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Comment removed"}',
            'request' => '{
   "id": "(int),
                    Comment id"
}',
            'description' => 'Delete comment',
        ),
    ),
    'Api\\V1\\Rpc\\UserDocsRemove\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document removed"}',
            'request' => '{
   "id": "(int),
                    Document id"
}',
        ),
    ),
    'Api\\V1\\Rpc\\LinksRemove\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Link removed"}',
            'request' => '{
   "id": "(int),
                    Link id"
}',
        ),
        'description' => 'Delete article link',
    ),
    'Api\\V1\\Rpc\\VideosRemove\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Video removed"}',
            'request' => '{
   "id": "(int),
                    Video id"
}',
        ),
        'description' => 'Delete video',
    ),
    'Api\\V1\\Rpc\\MessagesRemove\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Message Deleted"}',
            'request' => '{
   "id": "(int),
                    Message id"
}',
        ),
        'description' => 'Delete Message',
    ),
    'Api\\V1\\Rpc\\UserDocsGet\\Controller' => array(
        'description' => 'Get list of user documents,
some fields can be hidden depends on the requesting user access rights',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"doc_id":"141937","title":"*******","expiry_date":"*******","user_id":"52","name":"\\u042d\\u0440\\u0438\\u043a","surname":"*******","email":"*******"},{"doc_id":"141936","title":"*******","expiry_date":"*******","user_id":"52","name":"\\u042d\\u0440\\u0438\\u043a","surname":"*******","email":"*******"}],"total_results":"2","_page":1,"_limit":25}',
        ),
    ),
    'Api\\V1\\Rpc\\NotificationsGet\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"notif_id":"1339","not_type":"like","not_message":null,"time":"1465124163","user_id":"35504","email":"*******","surname":"*******","avatar":null,"cv_avatar":"seaman-50588.jpg","title":"b\\/officer\\/ bunker barge\\/ 7200USD","text":" urgent bunker officer "}],"total_results":"40","_page":1,"_limit":"1"}',
        ),
        'description' => 'Getting notifications list for the current user',
    ),
    'Api\\V1\\Rpc\\TagsGet\\Controller' => array(
        'description' => 'You can get list of tags, with additional information. You can filter results by providing filtering query. To get tags for appropriate post section and section_id should be provided.',
    ),
    'Api\\V1\\Rpc\\ChatsGetList\\Controller' => array(
        'description' => 'Get list of chats for current user',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"id":"121","from_id":"37790","to_id":"52","text":"al nice)","readed":"1","chat_id":"40","time":"1462815705","message_id":"121","member_id":"52","last_msg_user_id":"37790","last_msg_name":"\\u042d\\u0440\\u0438\\u043a","last_msg_surname":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","last_msg_full_name":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","last_msg_avatar":null,"last_msg_login":"zzzloy778","chat_with_name":"\\u042d\\u0440\\u0438\\u043a","chat_with_surname":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","chat_with_full_name":"","chat_with_avatar":"crop_A9dM5hV2dE7xL5v.jpg","chat_with_cv_avatar":"D7qL5qV6oC6bF2n.jpg","chat_with_login":"z_loy","unreaded_count":"0"}],"total_results":"8","_page":1,"_limit":"1"}',
            'description' => 'Returns following fields :

[id] => (int) Message id;
[from_id] =>  (int)  User id from who message was sent;
[to_id] =>  (int)  User id to who message addressed;
[text] => (text) Message text;
[readed] => (bool) if the message readed or not;
[chat_id] =>  (int) chat id;
[time] =>  (int) (unix_timestamp) time when message sent;
[message_id] =>  (int) message id;
[member_id] =>  (int) user id from who message was sent;
[last_msg_user_id] => (int) user id of the last message in chat;
[last_msg_name] => (varchar) name of the last message in chat Author;
[last_msg_surname] => (varchar) surname of the last message in chat Author;
[last_msg_full_name] => (varchar) full_name of the last message in chat Author;
[last_msg_avatar] => (varchar) avatar of the last message in chat Author;
[last_msg_login] => (varchar) login of the last message in chat Author;
[chat_with_name] => (varchar) name of the chat opponent;
[chat_with_surname] => (varchar) surname of the chat opponent;
[chat_with_full_name] => (varchar) full_name of the chat opponent;
[chat_with_avatar] => (varchar) avatar of the chat opponent;
[chat_with_cv_avatar] => (varchar) avatar of the chat opponent;
[chat_with_login] => (varchar) login of the chat opponent;
[unreaded_count] => (int) count of unreaded messages in chat;',
        ),
    ),
    'Api\\V1\\Rpc\\ChatsGetChat\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"message_id":"101","id":"101","from_name":"\\u042d\\u0440\\u0438\\u043a","from_surname":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","from_full_name":"","from_avatar":"crop_A9dM5hV2dE7xL5v.jpg","from_cv_avatar":"D7qL5qV6oC6bF2n.jpg","from_login":"z_loy"}],"total_results":"3","_page":1,"_limit":"1"}',
        ),
        'description' => 'Getting chat messages',
    ),
    'Api\\V1\\Rpc\\ChatsDeleteMessage\\Controller' => array(
        'description' => 'Delete message from chat',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Message Deleted"}',
            'request' => '',
            'description' => 'message id to be provided:',
        ),
    ),
    'Api\\V1\\Rpc\\CommentsEdit\\Controller' => array(
        'description' => 'Edit comment',
        'POST' => array(
            'description' => 'Comment id and text of comment to be provided',
            'request' => '{
   "id": "(int),
                    Comment id",
   "comment": "(text),
            Comment text"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Comment Saved!","affected_id":"148"}',
        ),
    ),
    'Api\\V1\\Rpc\\QuestionsEdit\\Controller' => array(
        'description' => 'Edit question',
        'POST' => array(
            'description' => 'Question id and other fields to be provided',
            'request' => '{
   "id": "(int),
                    Question id",
   "title": "(string), max 255 chars,
            Question title",
   "tags": "(string), max 255
            Comma separated list of tags for the question",
   "text": "(text),
            Text of the question",
   "anonym": "(bool),
            Does the author want to hide his name"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Question saved!","affected_id":"309"}',
        ),
    ),
    'Api\\V1\\Rpc\\LogbooksEdit\\Controller' => array(
        'description' => 'Edit logbook entry',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Logbook id",
   "tags": "(string), max 255
            Comma separated list of tags for the logbook",
   "text": "(text),
            Text of the logbook"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Logbook saved!","affected_id":"1435"}',
        ),
    ),
    'Api\\V1\\Rpc\\VacanciesEdit\\Controller' => array(
        'description' => 'Edit vacancy',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Vacancy id",
   "title": "(string), max 255
            Vacancy Title",
   "time": "(int), Unix Time Stamp
            Postpone publication",
   "text": "(text),
            Vacancy description",
   "rank": "(varchar), max 32
            Rank",
   "salary": "(int), max 6
            Salary",
   "salary_unit": "(varchar option), USD/EUR/GBP
            Salary currency",
   "ship_name": "(varchar), 32
            Ship Name",
   "ship_type": "(varchar), 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "ship_dwt": "(int), 10
            Ship Size / Dwt",
   "date_join": "(int), Unix Time
            Date Join",
   "contract_length": "(digits), days 1 - 999
            Contract length",
   "crew_nationality": "(varchar), 32
            Crew nationality",
   "english": "(digits), 1 - 5
            Minimum English Level",
   "comments": "(varchar), 255
            Any personal comments (visible only to vacancy author)",
   "urgent": "(bool), 0/1
                 is vacancy urgent?"
}',
        ),
    ),
    'Api\\V1\\Rpc\\QanswersEdit\\Controller' => array(
        'description' => 'Edit Answer',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Answer id",
   "text": "(text),
            Text of the answer",
   "anonym": "(bool), [default=0]
            Does the author want to hide his name"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Answer saved!","affected_id":"157173"}',
        ),
    ),
    'Api\\V1\\Rpc\\UserExperienceEdit\\Controller' => array(
        'description' => 'Edit user experience entry',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Experience record id",
   "ship_name": "(string), max 32
            Ship Name",
   "date_from": "(int) unix timestamp
                    Date when contract begin",
   "date_to": "(int) unix timestamp
                    Date when contract completed",
   "rank": "(string), max 32
            Rank",
   "flag": "(string), max 32
            Ship Flag",
   "type": "(string), max 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "dwt": "(int), 10
            Ship Size / Dwt",
   "grt": "(int), 10
            Ship Size / Gross Tonnage",
   "bhp": "(int), 10
            Engine Horse Powers (Kilowats)",
   "company": "(string), max 255
            Company/Owner/Crewing agency",
   "trading_area": "(string), max 1000 chars
            Trading area, ports visited during contract",
   "text": "(string), max 1000 chars
            Any additional information about this contract"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience saved!","affected_id":"162548"}',
        ),
    ),
    'Api\\V1\\Rpc\\UserDocsAdd\\Controller' => array(
        'description' => 'Adding new user document',
        'POST' => array(
            'request' => '{
   "title": "(string), max 255 chars,
            Document title",
   "number": "(string), max 32 chars,
            Document number",
   "english": "(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate",
   "grade": "(string), max 64 chars,
            Document grade",
   "issue_date": "(int), Unix Time
            Document issue date",
   "expiry_date": "(int), Unix Time
            Document expiry date",
   "issue_place": "(string), max 30 chars,
            Place of issue"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document added!","generated_id":"141939"}',
        ),
    ),
    'Api\\V1\\Rpc\\UserDocsEdit\\Controller' => array(
        'description' => 'Edit user document',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Document record id",
   "title": "(string), max 255 chars,
            Document title",
   "number": "(string), max 32 chars,
            Document number",
   "type": "(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate",
   "grade": "(string), max 64 chars,
            Document grade",
   "issue_date": "(int), Unix Time
            Document issue date",
   "expiry_date": "(int), Unix Time
            Document expiry date",
   "issue_place": "(string), max 30 chars,
            Place of issue"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document saved!","affected_id":"141939"}',
        ),
    ),
    'Api\\V1\\Rpc\\VacanciesToggleSubscribe\\Controller' => array(
        'description' => 'Toggle subscription to vacancy',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Vacancy record id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"You are subscribed to this vacancy, your CV sent to Company","status":"subscribed","subscribers_count":1}',
        ),
    ),
    'Api\\V1\\Rpc\\VacanciesToggleReport\\Controller' => array(
        'description' => 'Toggle reporting that vacancy is closed',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Vacancy record id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"You are reported for this vacancy, company will be informed by email","status":"report_sent","reports_count":1}',
        ),
    ),
    'Api\\V1\\Rpc\\QuestionsToggleSubscribe\\Controller' => array(
        'description' => 'Toggle subscription for the question answers',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Question record id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"You are subscribed to this question, you will be informed when answers found","status":"subscribed","subscribers_count":2}',
        ),
    ),
    'Api\\V1\\Rpc\\UsersInfoUnlock\\Controller' => array(
        'description' => 'Unlock user contact and personal information',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    User id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"User personal info unlocked","status":1,"user_info":{"user_id":"52","id":"52","login":"z_loy","role":"admin","type":"user","sex":"male","avatar":"crop_A9dM5hV2dE7xL5v.jpg","cv_avatar":"D7qL5qV6oC6bF2n.jpg","name":"\\u042d\\u0440\\u0438\\u043a","surname":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","full_name":"","email":"vasya@mail.ru","contact_mobile":"+32423423432","contact_mobile_2":"","contact_phone":"","contact_phone_2":"","desired_rank":"Master","minimum_salary":"12000","salary_currency":"USD","visa_usa":"2","visa_usa_exp":"1443650400","visa_shenghen":"0","visa_shenghen_exp":"1444082400","dob":"510879600","nationality":"Ukraine","place_of_residence":"Odessa","home_city":"","home_address":"","english_knowledge":"5","marital_status":"single","user_notes":"Ready to work","last_activity":"1468911214","in_db_date":"0","reg_date":"0","readiness_date":"1443996000","company_name":"","company_description":"","company_license":"0","social_vk_domain":"green_bucks"}}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileGet\\Controller' => array(
        'description' => 'Getting profile information for the current user',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"user_id":"37790","id":"37790","login":"zzzloy778"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileEdit\\Controller' => array(
        'description' => 'Edit current user profile information',
        'POST' => array(
            'request' => '{
   "login": "(string) (max 20) User login",
   "password": "(string) (max 32) Change user password",
   "desired_rank": "(string) User rank. Should be one of the acceptable ranks",
   "minimum_salary": "(int) (100 - 99999) Minimum salary, USD.",
   "visa_usa": "(bool) does user have USA visa",
   "visa_usa_exp": "(int), Unix Time
            USA Visa expiry date",
   "visa_shenghen": "(bool) does user have Shenghen visa",
   "visa_shenghen_exp": "(int), Unix Time
            Shenghen Visa expiry date",
   "name": "(string) (max 32) User name",
   "surname": "(string) (max 32) User surname",
   "sex": "(varchar option), male/female
            Gender",
   "dob": "(int), Unix Time
            Date of birth",
   "home_country": "(string) User country of living. Should be one of the acceptable countries",
   "nationality": "(string) User nationality. Should be one of the acceptable nationalities",
   "home_city": "(string) User city. Should be one of the acceptable cities",
   "home_address": "(string) (max 255) User home address",
   "contact_email": "(string) (max 255) User additional contact email",
   "contact_mobile": "(string) (max 255) User contact mobile",
   "contact_phone": "(string) (max 255) User contact phone",
   "info_website": "(string) (max 255) User web site",
   "english_knowledge": "(int) (1 - 5) English Knowledge level",
   "current_location": "(string) \'sea\' | \'home\'
                    Current location",
   "readiness_date": "(int), Unix Time
            Readiness date",
   "company_name": "(string) (max 64) Company name,
                to be used if user type is "Company" ",
   "company_description": "(text) Company description,
                to be used if user type is "Company" ",
   "company_license": "(string) (max 24) Company license number,
                to be used if user type is "Company" 
                "
}',
            'description' => '',
        ),
    ),
    'Api\\V1\\Rpc\\CountriesGet\\Controller' => array(
        'description' => 'Getting the list of countries',
        'GET' => array(
            'response' => '{
   "_fields": "Coma separated name of fields returned by the request. Acceptable fields are:
                    id, country_code, country_name",
   "_limit": "(int),
                    Limit for the results quantity per page",
   "_page": "(int) Page of the collection."
}',
        ),
    ),
    'Api\\V1\\Rpc\\ListRanksGet\\Controller' => array(
        'description' => 'Getting the list of ranks',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"rank":"Master"},{"rank":"Bar Waiter"},{"rank":"Hotel Manager"},{"rank":"Excavator driver"},{"rank":"Painter"}],"total_results":"128","_page":1,"_limit":0}',
        ),
    ),
    'Api\\V1\\Rpc\\ListCountriesGet\\Controller' => array(
        'description' => 'Getting list of countries',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"country_name":"United States"},{"country_name":"Canada"},{"country_name":"Afghanistan"},{"country_name":"Albania"},{"country_name":"Algeria"},{"country_name":"American Samoa"},{"country_name":"Andorra"},{"country_name":"Angola"},{"country_name":"Anguilla"},{"country_name":"Antarctica"},{"country_name":"Antigua and\\/or Barbuda"},{"country_name":"Argentina"},{"country_name":"Armenia"},{"country_name":"Aruba"},{"country_name":"Australia"},{"country_name":"Austria"},{"country_name":"Zambia"},{"country_name":"Zimbabwe"}],"total_results":"242","_page":1,"_limit":0}',
        ),
    ),
    'Api\\V1\\Rpc\\ListShipTypesGet\\Controller' => array(
        'description' => 'Getting the list of ship types.
Returns ship type, and how many vessels of this type are currently in database',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"type":"","count":"2957"},{"type":"Accommodation Barge","count":"144"},{"type":"Auxilary Naval Ships","count":"39"},{"type":"Barge","count":"243"},{"type":"Bulk Carrier","count":"31956"},{"type":"Bulk Carrier - Cement Carrier","count":"282"},{"type":"[OFFSHORE] Stand By Safety \\/ Guard Vessel","count":"71"},{"type":"[OFFSHORE] Survey Vessel","count":"149"},{"type":"[OFFSHORE] WSS - Well Stimulation Ship","count":"41"}],"total_results":"116","_page":1,"_limit":0}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileRegister\\Controller' => array(
        'description' => 'Register new user. Email of the user required.',
        'POST' => array(
            'description' => '- If user with such email NOT found in database:
will return status -\'201\' with message "New user registered. Please confirm email". Confirmation CODE will be sent to the stated email.
In that case request to action - /profile.confirm-email should be done with the CODE received on email;

- If user with such email found in database:
will return status - \'422\' with message "User with such E-mail already found in database. Please login."
In that case login screen should tobe be shown to the user.',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileRegStart\\Controller' => array(
        'description' => 'Start registration process of the new user. User email is required.',
        'POST' => array(
            'description' => '- If user with such email NOT found in database:
will return status -\'200\' .
Confirmation CODE will be sent to the stated email. Completion of the registration to be carried out.

- If user with such email found in database:
will return status - \'422\' with message "User with such E-mail already found in database. Please login."
In that case login process flow should to be carried out.',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"We send a confirmation code to you. Please check your email, to complete registration!"}',
            'request' => '{
   "email": "(string) (max 255) User email"
}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileRegComplete\\Controller' => array(
        'description' => 'Complete registration process for the new user. Additional profile information (login,name, surname etc..) can also be set in this action.',
        'POST' => array(
            'description' => 'Email confirmation key and password are required. 
After this action is successfully completed, user can login using his email and password.',
            'request' => '{
   "email_confirmation_key": "(varchar) 
                Confirmation key sent to user email during start of registration ",
   "password": "(string) (max 32) Change user password",
   "login": "(string) (max 20) User login",
   "name": "(string) (max 32) User name",
   "surname": "(string) (max 32) User surname",
   "desired_rank": "(string)
                    User desired rank, one of the ranks from ranks list",
   "minimum_salary": "(int) (100 - 99999) Minimum salary, USD.",
   "readiness_date": "(int), Unix Time
            Readiness date"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"Registration Successfull! You can now enter with your email and password"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileForgotPass\\Controller' => array(
        'description' => 'Change user password if he forgot it. Secret code will be sent to the users email if such found in database. With this code user can query action /profile.reset-pass and set new password',
        'POST' => array(
            'request' => '{
   "email": "(string) (max 255) User email"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Please check e-mail to reset your password"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileResetPass\\Controller' => array(
        'description' => 'Reset user password.',
        'POST' => array(
            'description' => 'Code sent to users email and new password are required.',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"Password change is Successfull! You can now enter with your email and password"}',
            'request' => '{
   "password_reset_key": "(varchar) 
                Confirmation key sent to user email during /profile.forgot-pass action ",
   "password": "(string) (max 32) Change user password"
}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileCvAvatarAdd\\Controller' => array(
        'description' => 'Upload user Application CV avatar. This avatar will be used as his CV photo. If user did not uppload main avatar, this photo will be used as avatar.',
        'POST' => array(
            'request' => '{
   "avatar": "User Application CV avatar image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png"
}',
            'response' => '{
	detail:"Avatar image saved!", 
	img_data:{
		thumb:"th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_www:"/uploads/pics/th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_w:360, 
		thumb_h:270, 
		base_url:"/uploads/pics/", 
		img:"Z2aY6gB7fA8kJ9y.jpg", 
		img_www:"/uploads/pics/Z2aY6gB7fA8kJ9y.jpg", 
		img_w:3200, 
		img_h:2400, 
		crop_w:"180", 
		crop_h:"270"
	}
}',
            'description' => 'If success, returns uploaded image object information.',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileAvatarRemove\\Controller' => array(
        'description' => 'Remove main user avatar',
        'POST' => array(
            'request' => '',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"Avatar removed"}',
            'description' => 'If success, current user main avatar will be deleted, and previously used avatar will be set.',
        ),
        'DELETE' => array(
            'description' => 'If success, current user main avatar will be deleted, and previously used avatar will be set.',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"Avatar removed"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileCvAvatarRemove\\Controller' => array(
        'description' => 'Remove user CV avatar',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"CV Avatar removed"}',
        ),
        'DELETE' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"CV Avatar removed"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileAvatarUpload\\Controller' => array(
        'description' => 'Upload user Main avatar. f user did not upload his cv avatar, this photo will be used instead.',
        'POST' => array(
            'description' => 'If success, returns uploaded image object information.',
            'request' => '{
   "avatar": "User avatar image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{
	detail:"Avatar image saved!", 
	img_data:{
		thumb:"th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_www:"/uploads/pics/th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_w:360, 
		thumb_h:270, 
		base_url:"/uploads/pics/", 
		img:"Z2aY6gB7fA8kJ9y.jpg", 
		img_www:"/uploads/pics/Z2aY6gB7fA8kJ9y.jpg", 
		img_w:3200, 
		img_h:2400
	}
}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileCvAvatarUpload\\Controller' => array(
        'description' => 'Upload user Application CV avatar. This avatar will be used as his CV photo. If user did not uppload main avatar, this photo will be used as avatar.',
        'POST' => array(
            'description' => 'If success, returns uploaded image object information.',
            'request' => '{
   "cv_avatar": "User Application CV avatar image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{
	detail:"Avatar image saved!", 
	img_data:{
		thumb:"th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_www:"/uploads/pics/th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_w:360, 
		thumb_h:270, 
		base_url:"/uploads/pics/", 
		img:"Z2aY6gB7fA8kJ9y.jpg", 
		img_www:"/uploads/pics/Z2aY6gB7fA8kJ9y.jpg", 
		img_w:3200, 
		img_h:2400
	}
}',
        ),
    ),
    'Api\\V1\\Rpc\\PicsAttachArticle\\Controller' => array(
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Images are attached to the article","total_pics":2}',
            'request' => '{
   "pics_ids": "Coma separated ids of the pics that have to be attached to post. Maximum 10 pics can be attached",
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id"
}',
        ),
        'description' => 'Attach uploaded pictures to some article. Maximum 10 pictures can be attached to article.',
    ),
    'Api\\V1\\Rpc\\PicsArticleRemove\\Controller' => array(
        'description' => 'Detach pictures from some article. If pics ids not sent, all pictures would be detached from article.',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Images are detached from the article","total_pics":"1"}',
            'request' => '{
   "pics_ids": "Coma separated ids of the pics that have to be attached to post. Maximum 10 pics can be attached",
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id"
}',
        ),
    ),
    'Api\\V1\\Rpc\\PicsGet\\Controller' => array(
        'description' => 'Getting list of pics',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"pic_id":"1042","id":"1042","img":"A7lM7fV9mO9fN7h.jpg","thumb":"th_A7lM7fV9mO9fN7h.jpg"},{"pic_id":"1041","id":"1041","img":"D7nR3zS4zA8cI6n.jpg","thumb":"th_D7nR3zS4zA8cI6n.jpg"}],"total_results":"2","_page":1,"_limit":"2"}',
        ),
    ),
    'Api\\V1\\Rpc\\PicsArticleAttach\\Controller' => array(
        'description' => 'Attach pictures to some article. Maximum 10 pictures can be attached to article.',
        'POST' => array(
            'request' => '{
   "pics_ids": "Coma separated ids of the pics that have to be attached to post. Maximum 10 pics can be attached",
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Images are attached to the article","total_pics":2}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileMenuGet\\Controller' => array(
        'description' => 'Get user menu items. Menu items also can be retrived with other site information in /site-info.get action',
        'GET' => array(
            'description' => 'Items of the user menu, depending on user role. For unauthorized user only few items would be returned. 
Consists of 2 arrays : 
- "Seacontact"  : main menu (left side menu on website version);
- "My" : user personal menu (right side menu on website version);',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":{"navigation":[{"Seacontact":["Home","News",{"Contacts":["Search","Collegues","Friends","Incoming Requests","Sent Requests","Subscribers"]},"Messages",{"Vacancies":["View Vacancy"]},"Companies DB",{"Questions":["Ask Question"]},{"Logbook":["Read Logbook Entry"]},"Notifications"]},{"MY":["CV Application","Personal Information","Experience","Documents","Settings"]}]}}',
        ),
    ),
    'Api\\V1\\Rpc\\SiteInfoGet\\Controller' => array(
        'description' => 'Getting overall information: users online, friends online, guests online, status, user menu, current user profile information, user rating information.',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":{"online_seamans":null,"online_seamans_count":"0","online_companies":null,"online_companies_count":"0","online_friends":null,"online_friends_count":"0","online_admins":null,"online_guests":"0","new_msgs":"1","new_contacts":"0","new_notif":"3","profile":{"id":"37790","login":"z_loy3","role":"sc_company","type":"user","sex":"","avatar":"crop_Y9iT8zQ6iA8aN1s.jpg","cv_avatar":null,"name":"\\u042d\\u0440\\u0438\\u043a","surname":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","full_name":"\\u042d\\u0440\\u043d\\u0435\\u0441\\u0442\\u043e\\u0432\\u0438\\u0447","email":"zzzloy777@gmail.com","contact_mobile":"","contact_mobile_2":"","contact_phone":"","contact_phone_2":"","desired_rank":"master","minimum_salary":"0","salary_currency":"USD","visa_usa":"1","visa_usa_exp":"0","visa_shenghen":"0","visa_shenghen_exp":"0","dob":"510879600","nationality":"Ukraine","place_of_residence":"","home_city":"","home_address":"","english_knowledge":"4","marital_status":"","user_notes":"35048","last_activity":"1470371299","in_db_date":"1454298159","reg_date":"1461402850","readiness_date":"1456268400","company_name":"Some Company","company_description":"","company_license":"","social_vk_domain":""},"rating":{"user_id":"37790","total":26.4,"profile":14.4,"experience":5,"documents":2,"activity":5,"max_exp_points":25,"max_doc_points":25,"max_profile_points":25,"max_activity_points":25},"navigation":[{"Seacontact":["Home","News",{"Vacancies":["View Vacancy"]},"Seamans DB","Companies DB",{"Questions":["Ask Question"]},{"Logbook":["Read Logbook Entry"]}]}]}}',
        ),
    ),
    'Api\\V1\\Rpc\\UserRatingGet\\Controller' => array(
        'description' => 'Getting user rating stats. If no user id provided, returns stats information for the current user',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":{"user_id":"37790","total":26.4,"profile":14.4,"experience":5,"documents":2,"activity":5,"max_exp_points":25,"max_doc_points":25,"max_profile_points":25,"max_activity_points":25}}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileDocsAdd\\Controller' => array(
        'description' => 'Adding new user document',
        'POST' => array(
            'request' => '{
   "title": "(string), max 255 chars,
            Document title",
   "number": "(string), max 32 chars,
            Document number",
   "english": "(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate",
   "grade": "(string), max 64 chars,
            Document grade",
   "issue_date": "(int), Unix Time
            Document issue date",
   "expiry_date": "(int), Unix Time
            Document expiry date",
   "issue_place": "(string), max 30 chars,
            Place of issue"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document added!","generated_id":"141939"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileDocsEdit\\Controller' => array(
        'description' => 'Edit user document',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Document record id",
   "title": "(string), max 255 chars,
            Document title",
   "number": "(string), max 32 chars,
            Document number",
   "type": "(digits), 1 - 2
            Document type: 1 - Passport, 2 - Certificate",
   "grade": "(string), max 64 chars,
            Document grade",
   "issue_date": "(int), Unix Time
            Document issue date",
   "expiry_date": "(int), Unix Time
            Document expiry date",
   "issue_place": "(string), max 30 chars,
            Place of issue"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document saved!","affected_id":"141939"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileDocsRemove\\Controller' => array(
        'description' => 'Remove user document',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Document id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Document removed"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileExperienceAdd\\Controller' => array(
        'description' => 'Adding contract to experience of the current user',
        'POST' => array(
            'request' => '{
   "ship_name": "(string), max 32
            Ship Name",
   "date_from": "(int) unix timestamp
                    Date when contract begin",
   "date_to": "(int) unix timestamp
                    Date when contract completed",
   "rank": "(string), max 32
            Rank",
   "ship_type": "(string), max 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "dwt": "(int), 10
            Ship Size / Dwt",
   "grt": "(int), 10
            Ship Size / Gross Tonnage",
   "bhp": "(int), 10
            Engine Horse Powers (Kilowats)",
   "company": "(string), max 255
            Company/Owner/Crewing agency",
   "trading_area": "(string), max 1000 chars
            Trading area, ports visited during contract",
   "text": "(string), max 1000 chars
            Any additional information about this contract"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience added!","generated_id":"175327"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileExperienceRemove\\Controller' => array(
        'description' => 'Remove user experience record',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Experience record id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience Record removed"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfileExperienceEdit\\Controller' => array(
        'description' => 'Edit user experience entry',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Experience record id",
   "ship_name": "(string), max 32
            Ship Name",
   "date_from": "(int) unix timestamp
                    Date when contract begin",
   "date_to": "(int) unix timestamp
                    Date when contract completed",
   "rank": "(string), max 32
            Rank",
   "flag": "(string), max 32
            Ship Flag",
   "type": "(string), max 32
            Ship Type",
   "ship_built": "(digits), year 1900 - 2020
            Ship Built",
   "dwt": "(int), 10
            Ship Size / Dwt",
   "grt": "(int), 10
            Ship Size / Gross Tonnage",
   "bhp": "(int), 10
            Engine Horse Powers (Kilowats)",
   "company": "(string), max 255
            Company/Owner/Crewing agency",
   "trading_area": "(string), max 1000 chars
            Trading area, ports visited during contract",
   "text": "(string), max 1000 chars
            Any additional information about this contract"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Experience saved!","affected_id":"162548"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfilePassForgot\\Controller' => array(
        'description' => 'Change user password if he forgot it. Secret code will be sent to the users email if such found in database. With this code user can query action /profile.reset-pass and set new password',
        'POST' => array(
            'request' => '{
   "email": "(string) (max 255) User email"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Please check e-mail to reset your password"}',
        ),
    ),
    'Api\\V1\\Rpc\\ProfilePassReset\\Controller' => array(
        'description' => 'Reset user password.',
        'POST' => array(
            'description' => 'Code sent to users email and new password are required.',
            'request' => '{
   "password_reset_key": "(varchar) 
                Confirmation key sent to user email during /profile.forgot-pass action ",
   "password": "(string) (max 32) Change user password"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{"detail":"Password change is Successfull! You can now enter with your email and password"}',
        ),
    ),
    'Api\\V1\\Rpc\\CommentsChangeRating\\Controller' => array(
        'description' => 'Change rating of comment',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Comment id",
   "rating": "(string) \'up\' | \'down\'
                    Rate comment up or down"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Success!","rating":{"up_votes":"0","down_votes":"1","soc_likes":"0","vote_status":"down"}}',
            'description' => 'User cannot change rating of its own comments.',
        ),
    ),
    'Api\\V1\\Rpc\\QanswersChangeRating\\Controller' => array(
        'description' => 'Change rating of the answer',
        'POST' => array(
            'request' => '{
   "id": "(int),
                    Answer id",
   "rating": "(string) \'up\' | \'down\'
                    Rate answer up or down"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Success!","rating":{"up_votes":"0","down_votes":"1","soc_likes":"0","vote_status":"down"}}',
            'description' => 'User cannot change rating of its own answers.',
        ),
    ),
    'Api\\V1\\Rpc\\QuestionsChangeRating\\Controller' => array(
        'description' => 'Change rating of question.
User cannot change rating of its own questions.',
        'POST' => array(
            'description' => 'User can vote up or down for the question.
If success returns information on current rate status of question:
up_votes - total count of up votes
down_votes - total count of down votes
soc_likes - total count of social votes
vote_status - vote status of current user',
            'request' => '{
   "id": "(int),
                    Question id",
   "rating": "(string) \'up\' | \'down\'
                    Rate question up or down"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Success!","rating":{"up_votes":"0","down_votes":"1","soc_likes":"4","vote_status":"down"}}',
        ),
    ),
    'Api\\V1\\Rpc\\VacanciesGet\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"vacancy_id":"248","id":"38678","title":"jhgfhgfhgf","company_id":"38678","for_company_company_name":null,"likes":"0","subscribe_status":null,"report_status":null,"vacancy_can_edit":0,"vacancy_can_delete":0},{"vacancy_id":"247","id":"52","title":"sdfsdf","company_id":"52","for_company_company_name":null,"likes":"0","subscribe_status":null,"report_status":null,"vacancy_can_edit":0,"vacancy_can_delete":0}],"total_results":"38","_page":1,"_limit":"2","img_base_url":"\\/uploads\\/pics\\/"}',
            'description' => '{
   "_limit": "(int),
                        Limit for the results quantity per page",
   "_page": "(int) Page of the collection.",
   "_user_fields": "Coma separated names of vacancy author fields.",
   "_vacancy_fields": "Comma separated field names for vacancy item;
            (refer to _vacancy_fields object) ",
   "_stats_fields": "Comma separated list of statistic fields (refer to stats_fields object)",
   "id": "(int) 
            Filter by Vacancy id",
   "company_id": "(int) 
            Filter vacancies by Company id",
   "ship_type": "varchar (32) 
            Filter vacancies by ship type",
   "rank": "varchar (32) 
            Filter vacancies by rank",
   "minimum_salary": "int (5) 
            Filter vacancies by minimum salary",
   "only_new": "bool (1) 
            Filter only new vacancies",
   "max_contract": "int (2) months 
            Filter vacancies with contract length less than value"
}',
        ),
        'description' => 'Get list of vacancies.

If "company_id" fields requested in "_vacancy_fields" during request, response will also state if vacancy can be edited or deleted by current user: "vacancy_can_edit" = 1; "vacancy_can_delete" = 1;',
    ),
    'Api\\V1\\Rpc\\QanswersApprove\\Controller' => array(
        'description' => 'Approve answer to the question.',
        'POST' => array(
            'description' => 'One of the answers to question, can be approved as correct. Only question author (or admins) can approve answers. Only one answer can be correct. If you approve one answer, other approved answer lose its correctness.',
        ),
    ),
    'Api\\V1\\Rpc\\QanswersToggleAccept\\Controller' => array(
        'description' => 'Toggle acceptance of the correct answer to the question.',
        'POST' => array(
            'description' => 'One of the answers to question, can be approved as correct. Only question author (or admins) can approve answers. Only one answer can be correct. If you approve one answer, other approved answer lose its correctness.',
            'request' => '{
   "id": "(int),
                    Answer id"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Answer accepted as correct!","affected_id":"162955","correct":1}',
        ),
    ),
    'Api\\V1\\Rpc\\VideosAdd\\Controller' => array(
        'description' => 'Add and attach youtube video to article.',
        'POST' => array(
            'description' => 'Link to youtube video, article section and section id to be provided. On success will return id of the attached video.',
            'request' => '{
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id",
   "url": "(string), max 255 chars,
            Youtube video link",
   "title": "(string), max 255 chars,
            Video title",
   "description": "(text), max 3600 chars,
            Video description"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Video added!","generated_id":"20"}',
        ),
    ),
    'Api\\V1\\Rpc\\LinksAdd\\Controller' => array(
        'description' => 'Add and attach link to article',
        'POST' => array(
            'description' => 'Link , article section and section id to be provided. On success will return id of the attached link.',
            'request' => '{
   "section": "(varchar) 
                Article section, refer to the _sections_object ",
   "section_id": "(int) 
                Article id",
   "url": "(string), max 255 chars,
            Youtube video link",
   "title": "(string), max 255 chars,
            Link title"
}',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Link added!","generated_id":"93"}',
        ),
    ),
    'Api\\V1\\Rpc\\VideosGet\\Controller' => array(
        'description' => 'Getting list of videos',
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"img_base_url":"\\/uploads\\/pics\\/","data_list":[{"id":"20","embed_url":"\\/\\/www.youtube.com\\/embed\\/YUn0BxE8cIs","thumb":null}],"total_results":"1","_page":1,"_limit":"50"}',
        ),
    ),
    'Api\\V1\\Rpc\\LinksGet\\Controller' => array(
        'description' => 'Get list of links',
    ),
    'Api\\V1\\Rpc\\VacanciesGetSubscribers\\Controller' => array(
        'GET' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"data_list":[{"subscribed_time":"1475844454","id":"3065","vacancy_id":"1116","user_id":"11405","time":"1475844454","name":"Vadym","surname":"KOVAL","full_name":"VADYM KOVAL ","login":"VADIM","avatar":"H7rH6uA4aI7nT1aL7tT3rV3rU5h.jpg","cv_avatar":"H7rH6uA4aI7nT1aL7tT3rV3rU5h.jpg","email":"*******","desired_rank":"Motorman-Electrician","nationality":"Ukraine","cv_last_call":"0","cv_last_update":"1464698460"},{"subscribed_time":"1475831928","id":"3054","vacancy_id":"1116","user_id":"71389","time":"1475831928","name":"Igor","surname":"Kusaiko","full_name":"","login":"XZYQRF","avatar":"crop_I9wE2bT4yQ2hV5f.jpg","cv_avatar":"I4vN9oO1jZ4iE6t.jpg","email":"*******","desired_rank":"3rd Engineer","nationality":"Ukraine","cv_last_call":"0","cv_last_update":"1470288298"}],"total_results":"3","_page":1,"_limit":"2","img_base_url":"\\/uploads\\/pics\\/"}',
        ),
        'description' => 'Get vacancy subscribers list',
    ),
    'Api\\V1\\Rpc\\ChatsSendMessage\\Controller' => array(
        'description' => 'Send message to chat or to user',
        'POST' => array(
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{"detail":"Message sent!","generated_id":"655","chat_id":"368"}',
            'description' => 'Returns id of generated message and chat_id',
        ),
    ),
    'Api\\V1\\Rpc\\PicsUpload\\Controller' => array(
        'description' => 'Upload image to server. Uploaded images can later be used to attach them to: logbook,vacancies,questions and other articles.',
        'POST' => array(
            'request' => '{
   "pic": "image file. Accepts file mimetypes: image/jpeg,image/jpg,image/pjpeg,image/png"
}',
            'description' => 'If action successfull, will return uploaded image data and image id. This id can be used for attaching to some article.',
            'response' => 'HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
{
	detail:"Image uploaded!", 
	img_data:{
                id:"237", 
		thumb:"th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_www:"/uploads/pics/th_Z2aY6gB7fA8kJ9y.jpg", 
		thumb_w:360, 
		thumb_h:270, 
		base_url:"/uploads/pics/", 
		img:"Z2aY6gB7fA8kJ9y.jpg", 
		img_www:"/uploads/pics/Z2aY6gB7fA8kJ9y.jpg", 
		img_w:3200, 
		img_h:2400

	}
}',
        ),
    ),
);

# seacontact
Social network for Seamans
Built with PHP / ZENDFRAMEWORK 2 / MYSQL / JQUERY / BOOTSTRAP 

Working version can be found on: http://seacontact.com

Public api is available at: http://api.seacontact.com

To build up your version of this platform, you need to have docker & docker-compose installed on your machine.

Once you ready write: 
```
git clone https://github.com/erik7z/seacontact.git
```

create .env file in the project root and update variables up to your environment:

```
SSL_CERT_HOST=seacontact.ru
NGINX_HOST=seacontact.ru
SEA_DOMAIN=seacontact.ru
_MYSQL_HOST_=db
_MYSQL_DB_NAME_=seacontact
_MYSQL_USER_=root
_MYSQL_PASSWORD_='#your_password#'
_REDIS_IP_='#redis_ip#
_REDIS_PORT_='#REDIS_PORT#

_GMAPS_KEY_='#GMAP_KEY#'
_DKIM_DOMAIN_=''
_DKIM_KEY_=''

_MBOFFICE_='#OFFICE_MAILBOX#'
_MBOFFICEPWD_='#OFFICE_MAILBOX_PASSWORD'
_MBCREW_='#CREWING_MAILBOX#'
_MBCREWPWD_='#CREWING_MAILBOX_PASSWORD'

_IN_APP_ID_=''
_IN_APP_SECRET_=''
_FB_APP_ID_=''
_FB_APP_SECRET_=''

_VK_APP_ID_=''
_VK_APP_SECR_KEY_=''
_VK_APP_CODE_=''
_VK_2APP_ID_=''
_VK_2APPSECR_KEY_=''
_VK_USER_=''
_VK_USER_SECRET_=''
_VK_USER_TOKEN_=''
_VK_GROUP_=''

```

Then build up project with:
```
docker-compose build
```

And run it with:
```
docker-compose up
```

After all files succesfully generated and client build completed, project will be available on your localhost.


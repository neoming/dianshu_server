## itemAdd
> use this function to add item to a book  

> @url
```
URL = servername/api/book/item/add+param
examples:
127.0.0.1/api/book/item/add?uid=3&api_token=UGoJacYPbon9uXJAANiVyWGg&book_id=2&type=0&position=1&character_id=1
```

> @param
+ uid ___int___ __用户id__
+ api_token __用户的token__
+ book_id ___int___ __对应图书的id__
+ type ___int___ __item类型：0,1,2分别代表文字，图片，声音__
+ position  ___int___  __item位置：0,1,2,分别代表左，中，右__
+ character_id ___int___ __item所有者的id也就是说这句话的人的id__
+ content ___string___ __item内容__

> @return  
> __json data of added item  or error_code__

## itemEdit
> use this function to edit exists item  

> @url
```
URL = servername/api/book/item/edit+param
examples:
127.0.0.1/api/book/item/add?uid=3&api_token=UGoJacYPbon9uXJAANiVyWGg&book_id=2&type=0&position=1&character_id=1&content=lalalala
```

> @param
+ uid ___int___ __用户id__
+ api_token __用户的token__
+ item_id ___int___ __
+ book_id ___int___ __对应图书的id__
+ type ___int___ __item类型：0,1,2分别代表文字，图片，声音__
+ position  ___int___  __item位置：0,1,2,分别代表左，中，右__
+ character_id ___int___ __item所有者的id也就是说这句话的人的id__
+ content ___string___ __item内容__ 

> @return  
> __json data of edited item  or error_code__

## itemRemove
> use this function to remove exists item  

> @url
```
URL = servername/api/book/item/remove+param
examples:
127.0.0.1/api/book/item/remove?uid=3&api_token=UGoJacYPbon9uXJAANiVyWGg&id=2
```

> @param
+ uid ___int___ __用户id__
+ api_token __用户的token__
+ id ___int___ __要删除的item的id__

> @return  
> __success or error_code__

## itemMove
> use this function to remove exists item  

> @url
```
URL = servername/api/book/item/remove+param
examples:
127.0.0.1/api/book/item/remove?uid=3&api_token=UGoJacYPbon9uXJAANiVyWGg&id=2&dest_id=3
```

> @detials  
> id = 2,dest_id = 4表示将id=2的item移动到现在id=4的item的位置

> @param
+ uid ___int___ __用户id__
+ api_token __用户的token__
+ id ___int___ __将要移动的item的id__
+ dest_id ___int___ __移动到对应位置的item对应的id__

> @return  
> __success or error_code__

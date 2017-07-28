# Oro\Bundle\TaskBundle\Entity\Task

## ACTIONS  

### get

Retrieve a specific task record.

{@inheritdoc}

### get_list

Retrieve a collection of task records.

{@inheritdoc}

### create

Create a new task record.
The created record is returned in the response.

{@inheritdoc}

{@request:json_api}
Example:

`</api/tasks>`

```JSON
{  
   "data":{  
      "type":"tasks",
      "attributes":{  
         "subject":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit",
         "description":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.",
         "dueDate":"2017-02-16T22:36:37Z"
      },
      "relationships":{  
         "taskPriority":{  
            "data":{  
               "type":"taskpriorities",
               "id":"normal"
            }
         },
         "status":{  
            "data":{  
               "type":"taskstatuses",
               "id":"open"
            }
         },
         "activityTargets":{  
            "data":[  
               {  
                  "type":"contacts",
                  "id":"61"
               },
               {  
                  "type":"accounts",
                  "id":"45"
               }
            ]
         }
      }
   }
}
```
{@/request}

### update

Edit a specific task record.

{@inheritdoc}

{@request:json_api}
Example:

`</api/tasks/1>`

```JSON
{  
   "data":{  
      "type":"tasks",
      "id":"1",
      "attributes":{  
         "subject":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit",
         "description":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.",
         "dueDate":"2017-02-16T22:36:37Z"
      },
      "relationships":{  
         "taskPriority":{  
            "data":{  
               "type":"taskpriorities",
               "id":"normal"
            }
         },
         "status":{  
            "data":{  
               "type":"taskstatuses",
               "id":"open"
            }
         },
         "activityTargets":{  
            "data":[  
               {  
                  "type":"contacts",
                  "id":"61"
               },
               {  
                  "type":"accounts",
                  "id":"45"
               }
            ]
         }
      }
   }
}
```
{@/request}

### delete

Delete a specific task record.

{@inheritdoc}

### delete_list

Delete a task records.
The list of records that will be deleted, could be limited by filters.

{@inheritdoc}

## FIELDS

### id

#### update

{@inheritdoc}

**The required field**

### status

#### create

{@inheritdoc}

**The required field**

### taskPriority

#### create

{@inheritdoc}

**The required field**

### subject

#### create

{@inheritdoc}

**The required field**

#### update

{@inheritdoc}

**Please note:**

*This field is **required** and must remain defined.*

## SUBRESOURCES

### owner

#### get_subresource

Retrieve the record of the user who is the owner of a specific task record.

#### get_relationship

Retrieve the ID of the user who is the owner of a specific task record.

#### update_relationship

Replace the owner of a specific task record.

{@request:json_api}
Example:

`</api/tasks/1/relationships/owner>`

```JSON
{
  "data": {
    "type": "users",
    "id": "37"
  }
}
```
{@/request}

### organization

#### get_subresource

Retrieve the record of the organization that a specific task belongs to.

#### get_relationship

Retrieve the ID of the organization that a specific task record belongs to.

#### update_relationship

Replace the organization that a specific task record belongs to.

{@request:json_api}
Example:

`</api/tasks/1/relationships/organization>`

```JSON
{
  "data": {
    "type": "organizations",
    "id": "1"
  }
}
```
{@/request}

### status

#### get_subresource

Retrieve status records configured for a specific task record

#### get_relationship

Retrieve the ID of the status record configured for a specific task record

#### update_relationship

Replace the status record configured for a specific task record

{@request:json_api}
Example:

`</api/tasks/1/relationships/status>`

```JSON
{
  "data": {
    "type": "taskstatuses",
    "id": "open"
  }
}
```
{@/request}

### taskPriority

#### get_subresource

Retrieve task priority records configured for a specific task record.

#### get_relationship

Retrieve the ID of the task priority records configured for a specific task record.

#### update_relationship

Replace the task priority configured for a specific task record.

{@request:json_api}
Example:

`</api/tasks/1/relationships/taskPriority>`

```JSON
{
  "data": {
    "type": "taskpriorities",
    "id": "normal"
  }
}
```
{@/request}

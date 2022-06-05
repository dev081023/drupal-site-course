import {useState} from "react";

function AnotherComponent({count}) {
  return <em>{count}</em>
}

export function MyComponent() {
  const [counter, setCounter] = useState(0)
  return <h1 onClick={() => setCounter(counter + 1)}>Hello Drupal from
    React <AnotherComponent count={counter}/></h1>
}

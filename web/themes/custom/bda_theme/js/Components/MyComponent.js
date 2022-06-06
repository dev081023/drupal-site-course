import {useEffect, useState} from "react";

export function MyComponent() {
  const [error, setError] = useState(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const [data, setData] = useState({});

  useEffect(() => {
    fetch(`${document.location.origin}/api/example/latest`)
      .then(res => res.json())
      .then(
        (result) => {
          setIsLoaded(true);
          setData(result)
        },
        (error) => {
          setIsLoaded(true);
          setData(error);
        }
      )
  }, [])

  if (error) {
    return <div>Error: {error.message}</div>;
  } else if (!isLoaded) {
    return <div>Loading...</div>;
  } else {
    return (<ul>{data.map((node) => <li><a className="use-ajax" href="{node.url}">{node.title}</a></li>)}</ul>);
  }
}
